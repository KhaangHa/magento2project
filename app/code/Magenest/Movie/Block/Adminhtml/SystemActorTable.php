<?php
namespace Magenest\Movie\Block\Adminhtml;
class SystemActorTable extends \Magento\Framework\App\Config\Value
{
    protected function _afterLoad()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $sql = "Select COUNT(*) FROM magenest_actor";
        $result = $connection->fetchAll($sql);
        $this->setValue($result[0]);

        parent::beforeSave();
    }

}