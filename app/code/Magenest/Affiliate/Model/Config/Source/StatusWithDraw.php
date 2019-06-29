<?php

namespace Magenest\Affiliate\Model\Config\Source;

use Magenest\Affiliate\Helper\Constant;

class StatusWithDraw implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Retrieve option array
     *
     * @return string[]
     */

    public function toOptionArray()
    {
        //        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = [
            Constant::AFFILIATE_WITHDRAW_APPROVED => __('APPROVED'),
            Constant::AFFILIATE_WITHDRAW_PENDING => __('PENDING'),
            Constant::AFFILIATE_WITHDRAW_DECLINED => __('DECLINED'),
            Constant::AFFILIATE_WITHDRAW_CANCEL => __('CANCELED'),
            Constant::AFFILIATE_WITHDRAW_ERROR => __('ERROR')
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
