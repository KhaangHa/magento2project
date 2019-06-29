<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 17:20
 */

namespace Magenest\Affiliate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Withdraw extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magenest_affiliate_withdraw', 'id');
    }
}
