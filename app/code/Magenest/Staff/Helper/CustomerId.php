<?php

namespace Magenest\Staff\Helper;


class CustomerId extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_customerSession;

    public function _construct(
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->_customerSession = $customerSession;
    }

    public function getCustomerId()
    {
        //return current customer ID
        return $this->_customerSession->getId();
    }
}