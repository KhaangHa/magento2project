<?php

namespace Magenest\Affiliate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Ppc extends AbstractDb
{

    public function _construct()
    {

        $this->_init("magenest_affiliate_ppc", "id");
        // TODO: Implement _construct() method.
    }
}