<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 21:07
 */

namespace Magenest\Affiliate\Controller\Account;

use Magenest\Affiliate\Helper\Constant;
use Magento\Framework\Exception\NotFoundException;

class WithdrawCancel extends \Magenest\Affiliate\Controller\Account
{
    protected $withdrawRepository;
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
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $customerId = $this->_customerSession->getCustomerId();
            $model = $this->withdrawRepository->getById($id);
            if ($model) {
                if ($customerId == $model->getCustomerId()) {
                    $model->setStatus(Constant::AFFILIATE_WITHDRAW_CANCEL);
                    $this->withdrawRepository->save($model);
                } else {
                    $this->messageManager->addErrorMessage("Something went wrong! Please contact us for more details");
                }
            } else {
                $this->messageManager->addErrorMessage("This Withdrawal is not exist! Please contact us for more details");
            }
        } else {
            throw new NotFoundException(__('Some Exception message.'));
        }
        return $this->_redirect('*/*/withdraw');
    }
}
