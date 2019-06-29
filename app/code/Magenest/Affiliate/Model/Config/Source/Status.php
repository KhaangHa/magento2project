<?php

namespace Magenest\Affiliate\Model\Config\Source;

use Magenest\Affiliate\Helper\Constant;

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
            Constant::AFFILIATE_CUSTOMER_APPROVED => __('APPROVED'),
            Constant::AFFILIATE_CUSTOMER_PENDING => __('PENDING'),
            Constant::AFFILIATE_CUSTOMER_DISABLED => __('DISABLED')
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
