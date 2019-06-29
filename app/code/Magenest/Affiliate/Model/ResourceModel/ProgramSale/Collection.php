<?php


namespace Magenest\Affiliate\Model\ResourceModel\ProgramSale;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\ProgramSale', 'Magenest\Affiliate\Model\ResourceModel\ProgramSale');
    }
}
