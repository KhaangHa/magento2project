<?php

namespace Magenest\Affiliate\Model\Source;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Percent')],
            ['value' => 1, 'label' => __('Fixed')],
        ];
    }
}
