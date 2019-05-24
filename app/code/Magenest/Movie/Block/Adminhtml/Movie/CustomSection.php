<?php
namespace Magenest\Movie\Block\Adminhtml\Movie;

use Magento\Framework\View\Element\Template;

class CustomSection extends Template
{
    public function setUp(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        return $connection;
    }

    public function getNumModule(){
        $connection = $this->setUp();

        $sql = "select count(*) as numModule from setup_module where  module NOT LIKE 'Magento%'";
        $result = $connection->fetchAll($sql);

        return $result[0]['numModule'];
    }
    public function getNumCustomer(){
        $connection = $this->setUp();

        $sql = "select count(*) as num from customer_entity";
        $result = $connection->fetchAll($sql);

        return $result[0]['num'];
    }
    public function getNumProduct(){
        $connection = $this->setUp();

        $sql = "select count(*) as num from catalog_product_entity";
        $result = $connection->fetchAll($sql);

        return $result[0]['num'];
    }
    public function getNumOrder(){
        $connection = $this->setUp();

        $sql = "select count(*) as num from sales_order";
        $result = $connection->fetchAll($sql);

        return $result[0]['num'];
    }
    public function getNumInvoice(){
        $connection = $this->setUp();

        $sql = "select count(*) as num from sales_invoice";
        $result = $connection->fetchAll($sql);

        return $result[0]['num'];
    }
    public function getNumCreditmemo(){
        $connection = $this->setUp();

        $sql = "select count(*) as num from sales_creditmemo";
        $result = $connection->fetchAll($sql);

        return $result[0]['num'];
    }
}