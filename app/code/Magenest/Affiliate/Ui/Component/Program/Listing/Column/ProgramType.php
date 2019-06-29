<?php

namespace Magenest\Affiliate\Ui\Component\Program\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class ProgramType extends \Magento\Ui\Component\Listing\Columns\Column
{

    private $_objectManager = null;

    protected $_programTypeFactory;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magenest\Affiliate\Model\ResourceModel\ProgramType\CollectionFactory $programTypeFactory,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_objectManager = $objectManager;
        $this->_programTypeFactory = $programTypeFactory;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $data = $this->_programTypeFactory->create()->getData();
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                foreach ($data as $dataItem) {
                    if ($item['program_type_id'] == $dataItem['id']) {
                        $value = $dataItem['name'];

                        $css = '<span style="font-weight: bold">' . $value . '</span>';

                        $item[$this->getData('name')] = html_entity_decode($css);

                        break;
                    }
                }

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
