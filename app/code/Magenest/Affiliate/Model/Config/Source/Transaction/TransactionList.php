<?php
namespace Magenest\Affiliate\Model\Config\Source\Transaction;

class TransactionList implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $sql = "Select entity_id FROM sales_order";
        $result = $connection->fetchAll($sql);

        $sql = "SELECT count(*) as quantity from sales_order";
        $num = $connection->fetchAll($sql);
        $outPut = [];
        for($i=0;$i<$num[0]['quantity'];$i++)
        {
            $outPut[$i]['value'] = $result[$i]['entity_id'];
            $outPut[$i]['label'] = $result[$i]['entity_id'];
        }
        return $outPut;
    }
}