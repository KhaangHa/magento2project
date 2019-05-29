<?php

namespace Magenest\Staff\Model\Config\Source;

class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('Lv1'), 'value'=>'1'],
            ['label' => __('Lv2'), 'value'=>'2'],
            ['label' => __('Not staff'), 'value'=>'3']
        ];

        return $this->_options;

    }

}