<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:57
 */

namespace Magenest\Affiliate\Model\Config\Program;


use Magenest\Affiliate\Model\Program;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve option array
     *
     * @return string[]
     */

    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $programType = $objectManager->create('\Magenest\Affiliate\Model\ResourceModel\ProgramType\Collection');

        $getUrl = $_SERVER['REDIRECT_URL'];
        if(strpos($getUrl,'addnew'))
        {
            $selectData = $programType->getSelect()->where('id',1);
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
            $connection = $resource->getConnection();


            $program = $connection->select()
                ->from($resource->getTableName('magenest_affiliate_program'), ['program_type_id']
                );


            $select = $connection->select()
                ->from($resource->getTableName('magenest_affiliate_program_type'))
                ->where('id not in (' . $program . ')');

            $programType = $connection->fetchAll($select);
        }


        $options[] = ['label' => '', 'value' => ''];
        $options[] = ['label' => '___Available Program(s)__', 'value' => '0'];
        foreach ($programType as $key => $value) {
            $options[] = [
                'label' => $value['name'],
                'value' => $value['id'],
            ];
        }
        return $options;
    }
}