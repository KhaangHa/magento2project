<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 03/02/2018
 * Time: 15:05
 */
namespace Magenest\Affiliate\Model\System\Config\Source;

use \Magento\SalesRule\Model\Rule;

class DiscountCouponType
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => Rule::BY_PERCENT_ACTION,
                'label'=>__('Percent of product price discount')
            ],
            [
                'value' => Rule::BY_FIXED_ACTION,
                'label'=>__('Fixed amount discount')
            ],
            [
                'value' => Rule::CART_FIXED_ACTION,
                'label'=>__('Fixed amount discount for whole cart')
            ],
            //            [
            //                'value' => Rule::BUY_X_GET_Y_ACTION,
            //                'label'=>__('Buy X get Y free (discount amount is Y)')
            //            ]
        ];
    }
}
