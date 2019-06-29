<?php
namespace Magenest\Affiliate\Controller\Account;

use Magento\Framework\App\ResponseInterface;

class Campaign extends \Magenest\Affiliate\Controller\Account
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
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->set(__('Affiliate Campaign'));

        return $this->_pageFactory->create();
    }
}