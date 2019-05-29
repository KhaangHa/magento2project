<?php

namespace Magenest\Staff\Block\Index\Search;
class SearchByName extends \Magento\Framework\View\Element\Template
{

    protected $_staffFactory;
    protected $_resource;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\Staff\Model\SubscriptionFactory $staffFactory,
        \Magento\Framework\App\ResourceConnection $Resource
    )
    {
        $this->_staffFactory = $staffFactory;
        $this->_resource = $Resource;

        parent::__construct($context);
    }

   public function getMyData($data){
       $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
       $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
       $connection = $resource->getConnection();

       $sql = "SELECT nick_name FROM magenest_staff WHERE nick_name like '%".$data."%'";
       $result = $connection->fetchAll($sql);

       return $result;

   }
}