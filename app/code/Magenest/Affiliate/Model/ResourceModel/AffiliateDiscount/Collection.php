<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:29
 */

namespace Magenest\Affiliate\Model\ResourceModel\AffiliateDiscount;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\AffiliateDiscount', 'Magenest\Affiliate\Model\ResourceModel\AffiliateDiscount');
    }
}
