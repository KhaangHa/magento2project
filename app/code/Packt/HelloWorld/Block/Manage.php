<?php

namespace Packt\HelloWorld\Block;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;

class Manage extends Template
{

    public function __construct(Context $context,\Magento\Store\Model\StoreManagerInterface $storeManager )
    {
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getBaseUrl()
    {
        return $this->getUrl('helloworld/index/manage');
    }

    public function getFNData()
    {
        return $this->getRequest()->getParam('firstname');
    }
    public function getLNData()
    {
        return $this->getRequest()->getParam('lastname');
    }
    public function getEmailData()
    {
        return $this->getRequest()->getParam('email');
    }
    public function getMessageData()
    {
        return $this->getRequest()->getParam('message');
    }
}