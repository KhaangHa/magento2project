<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:57
 */
namespace Magenest\Affiliate\Model\Config\Campaign;


use Magenest\Affiliate\Model\Campaign;

class Type implements \Magento\Framework\Data\OptionSourceInterface
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
            Campaign::TYPE_DISCOUNT => __('Actice'),
            Campaign::TYPE_PAY_PER_SALE  => __('Not Actice'),
            Campaign::TYPE_PAY_PER_CLICK => __('Actice'),
            Campaign::TYPE_PAY_PER_SALE => __('Not Actice'),
            Campaign::TYPE_PAY_PER_INPRESSION => __('Actice'),
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