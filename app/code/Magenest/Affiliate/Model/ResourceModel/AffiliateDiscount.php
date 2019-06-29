<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 16/03/2018
 * Time: 09:34
 */

namespace Magenest\Affiliate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AffiliateDiscount extends AbstractDb{

    public function _construct()
    {

        $this->_init("magenest_affiliate_discount","id");
        // TODO: Implement _construct() method.
    }
}