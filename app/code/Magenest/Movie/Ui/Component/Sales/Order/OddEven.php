<?php

namespace Magenest\Movie\Ui\Component\Sales\Order;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class OddEven extends \Magento\Ui\Component\Listing\Columns\Column
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
                if ($item['increment_id'] % 2 == 0) $value = 'even';
                    else $value = 'odd';
                switch ($value) {
                    case 'even':
                        $class = 'grid-severity-notice';
                        break;
                    case 'odd':
                    default:
                        $class = 'grid-severity-critical';
                        break;
                }
               $css = '<span class="' . $class . '"><span>' . $value . '</span></span>';

                $item[$this->getData('name')] = html_entity_decode($css);

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
