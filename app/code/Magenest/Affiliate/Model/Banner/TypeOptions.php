<?php
namespace Magenest\Affiliate\Model\Banner;

class TypeOptions implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['label' => 'Image', 'value' => 1],
            ['label' => 'Text', 'value' => 2]
        ];
    }
}