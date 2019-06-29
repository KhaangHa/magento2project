<?php

namespace Magenest\Affiliate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProgramType extends AbstractDb
{

    public function _construct()
    {
        $this->_init("magenest_affiliate_program_type", "id");
    }
}