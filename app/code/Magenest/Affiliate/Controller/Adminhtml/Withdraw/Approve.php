<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 19/08/2017
 * Time: 10:53
 */

namespace Magenest\Affiliate\Controller\Adminhtml\Withdraw;

use Magenest\Affiliate\Helper\Constant;
use Magenest\Affiliate\Model\PayPalMassPayService;

class Approve extends \Magenest\Affiliate\Controller\Adminhtml\Withdraw
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('withdraw_id');
        $withdraw = $this->withdrawRepository->getById($id);
        if ($withdraw) {
            $customerId = $withdraw->getCustomerId();
            $customer = $this->customerRepository->getByCustomerId($customerId);
            //check customer balance
            if ($customer) {
                $customerBalance = $customer->getBalance();
                $withdrawMoney = $withdraw->getMoney();
                if (floatval($customerBalance)<floatval($withdrawMoney)) {
                    //amount error
                    $withdraw->setData('status', Constant::AFFILIATE_WITHDRAW_DECLINED);
                    $this->withdrawRepository->save($withdraw);
                    $this->messageManager->addErrorMessage("Accept error: account balance");
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/');
                }
                if ($withdraw->getMethod()=="PAYPAL") {
                    //cal withdraw api
                    $currencyCode = strtoupper($this->_storeManager->getStore(null)->getBaseCurrencyCode());
                    $paypalEmail = $customer->getPaypalEmail();
                    $result = $this->paypalMassPaymentService
                        ->sendMassPayment(
                            PayPalMassPayService::PAYPAL_RECEIVE_TYPE_EMAIL,
                            $paypalEmail,
                            $withdrawMoney,
                            $currencyCode
                        );
                }
                if ($withdraw->getMethod()=="OFFLINE") {
                    $result['ACK']="Success";
                }
                if (!!$result) {
                    if (isset($result['ACK']) && ($result['ACK'] == 'Success')) {
                        //withdraw api call success
                        $withdraw->setData('status', Constant::AFFILIATE_WITHDRAW_APPROVED);
                        $this->withdrawRepository->save($withdraw);
                        $totalWithdraw = $customer->getTotalPaid();
                        $totalWithdraw+=$withdrawMoney;
                        $balanceAfter = $customerBalance - $withdrawMoney;
                        $customer->setBalance($balanceAfter);
                        $customer->setTotalPaid($totalWithdraw);
                        $this->customerRepository->save($customer);
                        $this->messageManager->addSuccessMessage("success");
                        $resultRedirect = $this->resultRedirectFactory->create();
                        return $resultRedirect->setPath('*/*/');
                    } else {
                        $withdraw->setData('status', Constant::AFFILIATE_WITHDRAW_DECLINED);
                        $this->withdrawRepository->save($withdraw);
                        $this->messageManager->addErrorMessage("Payment error");
                        $resultRedirect = $this->resultRedirectFactory->create();
                        return $resultRedirect->setPath('*/*/');
                    }
                }
            }
        }
    }
}
