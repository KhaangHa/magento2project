<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 07/03/2018
 * Time: 07:47
 */

namespace Magenest\Affiliate\Block;

use Magento\Framework\View\Element\Template;

class AffiliateDiscount extends Template
{
    public function getAffiliateDiscount()
    {
        return "$10";
    }
}
