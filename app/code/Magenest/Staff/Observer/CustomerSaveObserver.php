<?php

namespace Magenest\Staff\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;


class CustomerSaveObserver implements ObserverInterface
{


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getCustomer();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $type = $customer->getCustomAttributes()["staff_type"];

        $query = "INSERT INTO magenest_staff(customer_id, nick_name, type, status) VALUE ('" . $customer->getId() . "','" . $customer->getFirstname() . " " . $customer->getLastname() . "'," . $type->getValue() . ",2)";
//        $query = "INSERT INTO magenest_staff(customer_id, nick_name, type, status) VALUE (1,'name', 2,2)";
        $connection->query($query);
    }
}
