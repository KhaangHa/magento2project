<?php

namespace Packt\HelloWorld\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Element\Messages;
use Magento\Framework\View\Result\PageFactory;

class Result extends \Magento\Framework\App\Action\Action
{
    /**
     * Index action
     * @return $this
     */
    /** @var \Magento\Framework\View\Result\PageFactory
     * protected $resultPageFactory;
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}

//namespace Packt\HelloWorld\Controller\Index;
//
//class Change implements \Magento\Framework\Event\ObserverInterface
//{
//    public function execute(\Magento\Framework\Event\Observer $observer)
//    {
//        $displayText = $observer->getData('display');
//        $displayText->setDisplay('Catch magento 2 event successfully!!!');
//        return $this;
//    }
//}