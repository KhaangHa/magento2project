<?php

namespace Magenest\Affiliate\Model\System\Config\Source;

use \Magento\Sales\Model\Order;

class OrderStatus
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => Order::STATE_PROCESSING,
                'label'=>__('Create Invoice')
            ],
            [
                'value' => Order::STATE_COMPLETE,
                'label'=>__('Order Complete')
            ]
        ];
    }
}
