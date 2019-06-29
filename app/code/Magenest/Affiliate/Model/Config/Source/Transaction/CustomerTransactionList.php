<?php
namespace Magenest\Affiliate\Model\Config\Source\Transaction;

class CustomerTransactionList implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $sql = "Select customer_id FROM magenest_affiliate_customer";
        $result = $connection->fetchAll($sql);

        $sql = "SELECT count(*) as quantity from magenest_affiliate_customer";
        $num = $connection->fetchAll($sql);
        $outPut = [];
        for($i=0;$i<$num[0]['quantity'];$i++)
        {
            $outPut[$i]['value'] = $result[$i]['customer_id'];
            $outPut[$i]['label'] = $result[$i]['customer_id'];
        }
        return $outPut;
    }
}