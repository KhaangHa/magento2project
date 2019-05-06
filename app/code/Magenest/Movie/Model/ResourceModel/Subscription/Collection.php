<?php

namespace Magenest\Movie\Model\ResourceModel\Subscription;
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
//    protected function getJoinData()
//    {
//        $collection = $this->collectionFactory()->create()->getCollection();
//        $second_table_name = $this->_resource->getTableName('magenest_movie');
//
//        $collection->getSelect()->joinLeft(array('second' => $second_table_name),
//            'magenest_director.director_id = magenest_movie.director_id');
//        echo $collection->getSelect()->__toString();
//        exit();
//    }

    public function _construct()
    {
        $this->_init('Magenest\Movie\Model\Subscription',
            'Magenest\Movie\Model\ResourceModel\Subscription');
    }
}