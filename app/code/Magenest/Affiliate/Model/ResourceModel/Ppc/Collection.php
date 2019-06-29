<?php


namespace Magenest\Affiliate\Model\ResourceModel\Ppc;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\Ppc', 'Magenest\Affiliate\Model\ResourceModel\Ppc');
    }
}