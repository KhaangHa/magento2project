<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 19/08/2017
 * Time: 09:45
 */

namespace Magenest\Affiliate\Controller\Account;

use Magento\Framework\App\ResponseInterface;

class SaveSetting extends \Magenest\Affiliate\Controller\Account
{

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        if (!$this->_customerSession->isLoggedIn()) {
            /**
 * @var \Magento\Framework\Controller\Result\Redirect $resultRedirect
*/
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        $paypalEmail = $this->getRequest()->getParam('payment_email');
        $bankAccount = $this->getRequest()->getParam('bank_account');
        $bankName = $this->getRequest()->getParam('bank_name');
        $this->logger->critical($paypalEmail);
        $this->logger->critical($bankAccount);
        $this->logger->critical($bankName);
        $customerId = $this->_customerSession->getCustomerId();
        $customer = $this->customerRepository->getByCustomerId($customerId);

        if ($customer) {
            if ($customer->isAffiliate()) {
                $this->logger->critical("payment");
                $customer->setPaypalEmail($paypalEmail);
                $customer->setBankAccount($bankAccount);
                $customer->setBankName($bankName);
                $this->customerRepository->save($customer);
            }
        }

        $this->messageManager->addSuccessMessage("Your setting is saved");
        return $this->_redirect("*/account/setting");
    }
}
