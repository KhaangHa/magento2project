<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 17:21
 */

namespace Magenest\Affiliate\Model\ResourceModel\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\Order', 'Magenest\Affiliate\Model\ResourceModel\Order');
    }
}
