<?php

namespace Magenest\Staff\Block;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;

class StaffForm extends Template
{
    protected $customerSession;

    public function __construct(Context $context,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Customer\Model\Session $customerSession,
                                array $data = []
    )
    {
        $this->_storeManager = $storeManager;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getNameData()
    {
        return $this->getName();
    }

    public function getLevelData()
    {
        return $this->getLevel();
    }
    public function getCustomerId(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerSession = $objectManager->create("Magento\Customer\Model\Session");

        if($customerSession->isLoggedIn()){
            return $customerSession->getCustomerId();
        }
    }

}