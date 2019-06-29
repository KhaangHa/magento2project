<?php

namespace Magenest\Movie\Block;
class Hello extends \Magento\Framework\View\Element\Template
{

    protected $_helloModelFactory;
    protected $_resource;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\Movie\Model\MovieSubscriptionFactory $helloModelFactory,
        \Magento\Framework\App\ResourceConnection $Resource
    )
    {
        $this->_helloModelFactory = $helloModelFactory;
        $this->_resource = $Resource;

        parent::__construct($context);
    }

    protected function _prepareLayout()
    {
        $text = $this->getJoinData();
        $this->setText($text);
    }

    public function getPosts()
    {
        $collection = $this->_helloModelFactory->create()->getCollection();
        return $collection;
    }

    public function getJoinData()
    {
        $collection = $this->_helloModelFactory->create()->getCollection();


        $collection->join(['movie_actor' => $collection->getTable('magenest_movie_actor')],
            'main_table.movie_id = movie_actor.movie_id',
            ['movie id' => 'movie_id']
        )->join(
            ['actor' => $collection->getTable('magenest_actor')],
            'movie_actor.actor_id = actor.actor_id',
            ['actor name' => new \Zend_Db_Expr('group_concat(`actor`.name)')]
        )->join(['director' => $collection->getTable('magenest_director')],
            'main_table.director_id = director.director_id',
            ['director name' => 'name']
        );
        $collection->getSelect()->group('main_table.movie_id');
       // echo $collection->getSelect()->__toString();
        return $collection;

    }
    public function LoadMyData(){
        $collection = $this->getJoinData();
        $container = array();
        foreach ($collection as $item) {
            $count = count($container);
            $itemArray = array(
                "movie" => $item["name"],
                "director"=> $item["director name"],
                "actor" => $item["actor name"]
            );
            $container[$count]=$itemArray;
        }
       return $container;
    }

}