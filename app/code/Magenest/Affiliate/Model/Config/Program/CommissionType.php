<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:57
 */
namespace Magenest\Affiliate\Model\Config\Program;


use Magenest\Affiliate\Model\Program;

class CommissionType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve option array
     *
     * @return string[]
     */

    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
        $connection = $resource->getConnection();

        $select = $connection->select()->from($resource->getTableName('magenest_affiliate_program_commission_type'));

        $result = $connection->fetchAll($select);
        $options[] = ['label' => '', 'value' => ''];
        $options[] = ['label' => '---Select---', 'value' => '0'];
        foreach ($result as $key => $value) {
            $options[] = [
                'label' => $value['name'],
                'value' => $value['id'],
            ];
        }
        return $options;
    }
}