<?php
namespace Magenest\Movie\Block\Adminhtml;
class SystemMovieTable extends \Magento\Framework\App\Config\Value
{
    protected function _afterLoad()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $sql = "SELECT COUNT(*) FROM magenest_movie";
        $result = $connection->fetchAll($sql);
        $this->setValue($result[0]);
        parent::beforeSave();
    }

}