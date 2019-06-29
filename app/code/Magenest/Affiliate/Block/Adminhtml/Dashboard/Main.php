<?php

namespace Magenest\Affiliate\Block\Adminhtml\Dashboard;
class Main extends \Magento\Framework\View\Element\Template
{

    protected $campaignFactory;
    protected $_resource;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\Affiliate\Model\CampaignFactory $campaignFactory,
        \Magento\Framework\App\ResourceConnection $Resource
    )
    {
        $this->campaignFactory = $campaignFactory;
        $this->_resource = $Resource;

        parent::__construct($context);
    }

    public function getConnection()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        return $resource->getConnection();
    }

    protected function _prepareLayout()
    {
        $text = $this->getJoinData();
        $this->setText($text);
    }

    public function getPosts()
    {
        $collection = $this->campaignFactory->create()->getCollection();
        return $collection;
    }

    public function getJoinData()
    {
        $collection = $this->campaignFactory->create()->getCollection();
        return $collection;

    }

    public function getCountData()
    {
        $connection = $this->getConnection();
        $sql = "Select COUNT(*) FROM magenest_affiliate_campaign";
        $result = $connection->fetchOne($sql);
        return $result;
    }

    public function getCustomer()
    {
        $connection = $this->getConnection();
        $sql = "Select *
                FROM magenest_affiliate_log
                ORDER BY id DESC LIMIT 10";
        $result = $connection->fetchAll($sql);

        $container = array();
        foreach ($result as $item) {
            $count = count($container);
            $itemArray = array(
                "description" => $item["description"],
                "id" => $item["id"]
            );
            if($item["type"] == 1)
                $itemArray["type"] = 	"&#128101;";
            else if($item["type"] == 2)
                $itemArray["type"] = "&#128184;";
            $container[$count] = $itemArray;
        }
        return $container;
    }

    public function LoadMyData()
    {
        $collection = $this->getJoinData();
        $container = array();
        foreach ($collection as $item) {
            $count = count($container);
            $itemArray = array(
                "name" => $item["name"],
                "description" => $item["description"],
                "start_time" => $item["start_time"],
                "end_time" => $item["end_time"]
            );
            $container[$count] = $itemArray;
        }
        return $container;
    }

}