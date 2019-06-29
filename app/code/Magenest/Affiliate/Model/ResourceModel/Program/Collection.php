<?php


namespace Magenest\Affiliate\Model\ResourceModel\Program;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\Program', 'Magenest\Affiliate\Model\ResourceModel\Program');
    }

//    protected function _initSelect()
//    {
//        parent::_initSelect();
//
//        $this->getSelect()->joinLeft(
//            ['secondTable' => $this->getTable('magenest_affiliate_program_config_commission')],
//            'main_table.id = secondTable.program_id',
//            ['*']
//        );
//
//        return $this;
//    }
}
