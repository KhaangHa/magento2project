<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:57
 */
namespace Magenest\Affiliate\Model\Config\Campaign;


use Magenest\Affiliate\Model\Campaign;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Retrieve option array
     *
     * @return string[]
     */

    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = [
            Campaign::STATUS_ACTIVE=> __('Actice'),
            Campaign::STATUS_NOT_ACTIVE => __('Not Actice'),

        ];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}