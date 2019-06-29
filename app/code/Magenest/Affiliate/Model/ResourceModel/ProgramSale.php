<?php

namespace Magenest\Affiliate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProgramSale extends AbstractDb
{

    public function _construct()
    {
        $this->_init("magenest_affiliate_program_commission_by_type", "id");
    }
}