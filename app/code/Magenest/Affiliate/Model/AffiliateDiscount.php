<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 16/03/2018
 * Time: 09:29
 */
namespace Magenest\Affiliate\Model;

use \Magento\SalesRule\Model\Rule;
use Magento\Framework\Model\AbstractModel;

class AffiliateDiscount extends AbstractModel
{
    const TYPE_CONFIG = "config";
    const TYPE_CAMPAIGN = "campaign";
    const ACTION_PER_PRODUCT = Rule::BY_PERCENT_ACTION;
    const ACTION_BY_FIXED  = Rule::BY_FIXED_ACTION;
    const ACTION_CART_FIXED = Rule::CART_FIXED_ACTION;

    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\ResourceModel\AffiliateDiscount');
        $this->setIdFieldName('id');
    }
}
