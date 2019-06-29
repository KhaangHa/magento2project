<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:57
 */
namespace Magenest\Affiliate\Model\Config\Program;


use Magenest\Affiliate\Model\Program;

class SelectProgram implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve option array
     *
     * @return string[]
     */

    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $programType = $objectManager->create('\Magenest\Affiliate\Model\ResourceModel\Program\Collection')->getData();

        $options[] = ['label' => '', 'value' => ''];
        $options[] = ['label' => '--Select--', 'value' => '0'];
        foreach ($programType as $key => $value) {
            $options[] = [
                'label' => $value['name'],
                'value' => $value['id'],
            ];
        }
        return $options;
    }
}