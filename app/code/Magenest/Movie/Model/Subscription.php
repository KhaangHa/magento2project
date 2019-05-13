<?php

namespace Magenest\Movie\Model;

class Subscription extends \Magento\Framework\Model\AbstractModel
{

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {

        parent::__construct($context, $registry, $resource,
            $resourceCollection, $data);
    }

    public function _construct()
    {
        //was this on 8/5
//        $this->_init('Magenest\Movie\Model\ResourceModel\Subscription');
        $this->_init('Magenest\Movie\Model\Resource\Director\Subscription');
    }
}