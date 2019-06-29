<?php

namespace Magenest\Affiliate\Model\System\Config\Source;

class CommissionType
{
    const COMMISSION_FIXED_PER_ITEM = 'fixed';
    const COMMISSION_PERCENTAGE = 'percentage';
    const COMMISSION_FIXED_PER_CART = 'cart_fixed';
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::COMMISSION_PERCENTAGE,
                'label'=>__('Percentage for order Total')
            ],
            [
                'value' => self::COMMISSION_FIXED_PER_CART,
                'label'=>__('Fixed amount for whole cart')
            ],
            [
                'value' => self::COMMISSION_FIXED_PER_ITEM,
                'label'=>__('Fixed Amount each Item')
            ]
        ];
    }
}
