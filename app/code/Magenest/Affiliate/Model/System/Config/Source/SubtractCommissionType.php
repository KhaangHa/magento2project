<?php

namespace Magenest\Affiliate\Model\System\Config\Source;

class SubtractCommissionType
{
    const SUBTRACT_FIXED = 'fixed';
    const SUBTRACT_PERCENTAGE = 'percentage';
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::SUBTRACT_PERCENTAGE,
                'label'=>__('Percentage of commission')
            ],
            //            [
            //                'value' => self::SUBTRACT_FIXED,
            //                'label'=>__('Fixed Amount of commission')
            //            ]
        ];
    }
}
