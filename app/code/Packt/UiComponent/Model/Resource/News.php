<?php
namespace Packt\UiComopnent\Model\Resource;
class News extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    public function _construct() {
        $this->_init('packt_helloworld_subscription',
            'subscription_id');
    }
}