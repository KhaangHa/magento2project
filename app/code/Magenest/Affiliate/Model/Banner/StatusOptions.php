<?php
namespace Magenest\Affiliate\Model\Banner;

class StatusOptions implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['label' => '--Select--', 'value' => 0],
            ['label' => 'Active', 'value' => 1],
            ['label' => 'Disable', 'value' => 2]
        ];
    }
}