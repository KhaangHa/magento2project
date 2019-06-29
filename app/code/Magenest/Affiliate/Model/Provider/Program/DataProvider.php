<?php

namespace Magenest\Affiliate\Model\Provider\Program;

use Magenest\Affiliate\Model\ResourceModel\Program\CollectionFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;
    protected $_configCollection;
    protected $_saleCollection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $programCollectionFactory,
        \Magenest\Affiliate\Model\ResourceModel\ProgramConfig\CollectionFactory $configCollection,
        \Magenest\Affiliate\Model\ResourceModel\ProgramSale\CollectionFactory $_saleCollection,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $programCollectionFactory->create();
        $this->_configCollection = $configCollection->create();
        $this->_saleCollection = $_saleCollection->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {

        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        $extend = [];
        $extend1 = [];
            foreach ($items as $contact)
            {
                $configs = $this->_configCollection->getItemsByColumnValue('program_id', $contact->getId());
                foreach ($configs as $config) {
                    array_push($extend, $config->getData());
                }

                $sales = $this->_saleCollection->getItemsByColumnValue('program_id', $contact->getId());
                foreach ($sales as $sale) {
                    array_push($extend1, $sale->getData());
                }

                //add config to data provider
                $contact->setData('dynamic_rows_container', $extend);
                $contact->setData('dynamic_row_sale', $extend1);

                $this->_loadedData[$contact->getId()] = $contact->getData();
                $this->_loadedData[$contact->getId()]['do_we_hide_it'] = true;
            }


        return $this->_loadedData;
    }

}