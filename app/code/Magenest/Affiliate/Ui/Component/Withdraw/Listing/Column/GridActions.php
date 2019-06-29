<?php
namespace Magenest\Affiliate\Ui\Component\Withdraw\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class GridActions extends Column
{
    /**
 * Url path
*/
    const GRID_URL_PATH_EDIT = 'affiliate/withdraw/edit';
    const GRID_URL_PATH_VIEW = 'affiliate/withdraw/view';
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var string
     */
    private $editUrl;
    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     * @param string             $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::GRID_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    /**
     * Prepare Data Source
     *
     * @param  array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['id'])) {
                    //                    $item[$name]['edit'] = [
                    //                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['id' => $item['id']]),
                    //                        'label' => __('Edit')
                    //                    ];
                    $item[$name]['view'] = [
                        'href' => $this->urlBuilder->getUrl(self::GRID_URL_PATH_VIEW, ['id' => $item['id']]),
                        'label' => __('View')
                    ];
                }
            }
        }
        return $dataSource;
    }
}
