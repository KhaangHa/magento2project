<?php
namespace Magenest\Affiliate\Model\Provider\Banner;

use Magenest\Affiliate\Model\ResourceModel\Banner\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $contactCollectionFactory,
        array $meta = [],
        array $data = []
    ){
        $this->collection = $contactCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if(isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems();

        foreach($items as $item)
        {
            $itemData = $item->getData();

            $itemData['image'] = array(
                array(
                    'name'  =>  'banner',
                    'url'   =>  $item['image'] // Should return a URL to view the image. For example, http://domain.com/pub/media/../../imagename.jpeg
                )
            );
            $this->_loadedData[$item->getId()] = $itemData;
        }

        return $this->_loadedData;
    }

}