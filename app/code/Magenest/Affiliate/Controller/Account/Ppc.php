<?php

namespace Magenest\Affiliate\Controller\Account;

use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;


class Ppc extends \Magento\Framework\App\Action\Action
{
    /** @var Registration */
    protected $registration;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param PageFactory $resultPageFactory
     * @param Registration $registration
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        PageFactory $resultPageFactory,
        Registration $registration,
        \Magenest\Affiliate\Helper\ConfigHelper $helper
    ) {
        $this->session = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->registration = $registration;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Customer register form page
     *
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $param_code = 'code';
        $paramsRequest = $this->getRequest()->getParams();
        if (!empty($paramsRequest) && !empty($paramsRequest['bannerid']) && !empty($paramsRequest['url']) && !empty($paramsRequest[$param_code])){
            $this->helper->countClickBanner($paramsRequest['bannerid'],$paramsRequest[$param_code]);
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath($_REQUEST['url']);
            return $resultRedirect;
        }
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;

//        if(isset($_REQUEST[$param_code]) && isset($_REQUEST['bannerid']) && isset($_REQUEST['url'])) {
//            $this->helper->countClickBanner($_REQUEST['bannerid'], $_REQUEST[$param_code]);
//
//            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
//            $resultRedirect = $this->resultRedirectFactory->create();
//            $resultRedirect->setPath($_REQUEST['url']);
//            return $resultRedirect;
//        }
//
//        /** @var \Magento\Framework\View\Result\Page $resultPage */
//        $resultPage = $this->resultPageFactory->create();
//        return $resultPage;
    }
}