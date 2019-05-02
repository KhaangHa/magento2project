<?php

namespace Packt\UiComponent\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
//        $resultPage = $this->resultPageFactory->create();
//        $resultPage->setActiveMenu('Packt_MyModule::index');
//        $resultPage->addBreadcrumb(__('HelloWorld'), __('HelloWorld'));
//        $resultPage->addBreadcrumb(__('ManageSubscriptions'), __('Manage Subscriptions'));
//        $resultPage->getConfig()->getTitle()->prepend(__('Subscriptions'));
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Packt_MyModule::index');
    }
}