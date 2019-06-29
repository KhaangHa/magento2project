<?php
namespace Magenest\Movie\Model\Config\Source;

class DirectorList implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $sql = "Select name,director_id FROM magenest_director";
        $result = $connection->fetchAll($sql);

        $sql = "SELECT count(*) as quantity from magenest_director";
        $num = $connection->fetchAll($sql);
        $outPut = [];
        for($i=0;$i<$num[0]['quantity'];$i++)
        {
            $outPut[$i]['value'] = $result[$i]['director_id'];
            $outPut[$i]['label'] = $result[$i]['name'];
        }
        return $outPut;
    }
}