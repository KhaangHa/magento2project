<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\Affiliate\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class Method implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            [
                'value' => 'offline',
                'label' => __('Offline'),
            ],
            [
                'value' => 'paypal',
                'label' => __('PayPal'),
            ],
            [
                'value' => 'others',
                'label' => __('Others')
            ]
        ];
    }
}
