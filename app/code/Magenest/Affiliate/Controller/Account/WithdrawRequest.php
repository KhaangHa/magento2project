<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 21:07
 */

namespace Magenest\Affiliate\Controller\Account;

use Magento\Framework\Exception\NotFoundException;

class WithdrawRequest extends \Magenest\Affiliate\Controller\Account
{
    /**
     * @var \Magenest\Affiliate\Api\WithdrawRepositoryInterface
     */
    protected $withdrawRepository;
    /**
     * @var \Magenest\Affiliate\Api\Data\WithdrawInterfaceFactory
     */
    protected $withdrawInterfaceFactory;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magenest\Affiliate\Api\Data\WithdrawInterfaceFactory $withdrawInterfaceFactory,
        \Magenest\Affiliate\Api\WithdrawRepositoryInterface $withdrawRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->withdrawRepository = $withdrawRepository;
        $this->withdrawInterfaceFactory = $withdrawInterfaceFactory;
        parent::__construct($context, $resultJsonFactory, $customerSession, $pageFactory, $customerRepository, $customerInterfaceFactory, $configHelper, $affiliateManagement, $logger);
    }
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
        $post = $this->getRequest()->getPost();
        $this->logger->critical(print_r($post, true));
        $params = $post->toArray();
        $check = true;
        if (count($params) >=2) {
            try {
                $customerId = $this->_customerSession->getCustomer()->getId();
                $customer = $this->customerRepository->getByCustomerId($customerId);
                if ($customer->getBalance() < $params['amount']) {
                    $this->messageManager->addErrorMessage("Your balance is lower than the withdraw amount.");
                    $this->_redirect("*/*/withdraw");
                } else {
                    //Paypal withdraw method
                    if ($params['payment'] == \Magenest\Affiliate\Model\Withdraw::PAYMENT_METHOD_PAYPAL) {
                        $this->logger->critical("paypal");
                        $customerEmail = $customer->getPaypalEmail();
                        if ($params['paypal_email'] === $customerEmail) {
                            $withdraw = $this->withdrawInterfaceFactory->create();
                            $withdraw->setData('customer_id', $customerId);
                            $withdraw->setData('money', $params['amount']);
                            $withdraw->setData('method', $params['payment']);
                            $this->withdrawRepository->save($withdraw);
                            $this->messageManager->addSuccessMessage("Request Success");
                            $this->_redirect("*/*/withdraw");
                        } else {
                            $this->messageManager->addErrorMessage("Something went wrong. Please try again!");
                            $this->_redirect("*/*/withdraw");
                        }
                    } elseif ($params['bank_account']) {
                        $bankTransferInfo=[];
                        $i=-1;
                        foreach ($params as $key => $param) {
                            $i++;
                            if ($i==0) {
                                continue;
                            }
                            if ($i==7) {
                                break;
                            }
                            $bankTransferInfo[$key]=$param;
                        }
                        $bankTransferInfo = json_encode($bankTransferInfo);
                        $customer->setBankAccount($bankTransferInfo);
                        $customer->save();
                        $this->logger->critical(print_r($bankTransferInfo, true));
                        $withdraw = $this->withdrawInterfaceFactory->create();
                        $withdraw->setData('customer_id', $customerId);
                        $withdraw->setData('money', $params['amount']);
                        $withdraw->setData('method', $params['payment']);
                        $this->withdrawRepository->save($withdraw);
                        $this->messageManager->addSuccessMessage("Request Success");
                        $this->_redirect("*/*/withdraw");
                    }
                }
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage("Something went wrong. Please try again!");
                $this->_redirect("*/*/withdraw");
            }
        } else {
            throw new NotFoundException(__('Some Exception message.'));
        }
    }
}
