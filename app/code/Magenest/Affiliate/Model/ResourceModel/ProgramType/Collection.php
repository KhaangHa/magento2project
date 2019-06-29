<?php


namespace Magenest\Affiliate\Model\ResourceModel\ProgramType;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\ProgramType', 'Magenest\Affiliate\Model\ResourceModel\ProgramType');
    }
}