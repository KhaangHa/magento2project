<?php
namespace Magenest\Movie\Plugin;


class ConfigPlugin
{
    public function beforeSave(
        \Magento\Config\Model\Config $subject
    )
    {
        $data = $subject->getData('groups/moviepage/fields/text_field/value');
        if(strtolower($data) == 'pong')
            $subject->setDataByPath('salesforce/moviepage/text_field','Ping');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('magenest_actor'); //gives table name with prefix

        //Select Data from table
        $sql = "Select COUNT(*) FROM " . $tableName;
        $result = $connection->fetchAll($sql);
        // gives associated array, table fields as key in array.
        $subject->setDataByPath('salesforce/moviepage/row_in_table_actor',$result[0]);


        //FILL TABLE
        $sql = "SELECT COUNT(*) FROM magenest_movie";
        $result = $connection->fetchAll($sql);
        $subject->setDataByPath('salesforce/moviepage/row_in_table',$result[0]);
    }

}
