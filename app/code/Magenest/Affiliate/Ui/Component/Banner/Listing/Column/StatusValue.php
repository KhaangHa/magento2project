<?php

namespace Magenest\Affiliate\Ui\Component\Banner\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class StatusValue extends \Magento\Ui\Component\Listing\Columns\Column
{

    private $_objectManager = null;


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
                if ($item['status'] == 1) $value = 'Active';
                else $value = 'Disable';
                switch ($value) {
                    case 'Active':
                        $class = 'grid-severity-notice';
                        break;
                    case 'Disable':
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
