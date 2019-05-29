<?php

namespace Magenest\Staff\Model\Resource\Subscription;
/**
 * Subscription Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */

    public function _construct()
    {
        $this->_init('Magenest\Staff\Model\Subscription',
            'Magenest\Staff\Model\Resource\Subscription');
    }
}