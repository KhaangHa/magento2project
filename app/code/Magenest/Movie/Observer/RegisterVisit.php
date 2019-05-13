<?php

namespace Magenest\Movie\Observer;

use Magento\Framework\Event\ObserverInterface;

class RegisterVisit implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getCustomer();
        $customer->setFirstname('magenest');
    }
}