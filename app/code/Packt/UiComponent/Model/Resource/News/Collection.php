<?php
namespace Packt\UiComponent\Model\Resource\News;
/**
 * Subscription Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct() {
        $this->_init('Packt\UiComponent\Model\News',
            'Packt\UiComponent\Model\Resource\News');
    }
}