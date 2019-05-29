<?php
namespace Magenest\Staff\Model\Resource;
class Subscription extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    public function _construct() {
        $this->_init('magenest_staff','id');
    }
}