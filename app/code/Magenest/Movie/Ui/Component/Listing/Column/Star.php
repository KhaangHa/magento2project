<?php

namespace Magenest\Movie\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Star extends \Magento\Ui\Component\Listing\Columns\Column
{


    private $_objectManager = null;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Webkul\Hello\Model\Image\Image $imageHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_objectManager = $objectManager;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
//
                $goldStar = "<span style=\"color: orange\">★</span>";
                $normalStar = "★";
                $numGoldStar = $item['rating']/2;
                $starArray = "";
                for($i = 0 ; $i < 5; $i++)
                {
                    if($i<$numGoldStar)
                        $starArray .= $goldStar . " ";
                    else $starArray .= $normalStar . " ";
                }

                $item[$this->getData('name')] = html_entity_decode($starArray);

            }
        }

        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
}