<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:27
 */
namespace Magenest\Affiliate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Campaign extends AbstractDb{

    public function _construct()
    {

        $this->_init("magenest_affiliate_campaign","id");
        // TODO: Implement _construct() method.
    }
}