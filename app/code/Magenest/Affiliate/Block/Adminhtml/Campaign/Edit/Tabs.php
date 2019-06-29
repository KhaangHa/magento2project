<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 07/10/2016
 * Time: 16:42
 */
namespace Magenest\Affiliate\Block\Adminhtml\Campaign\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Campaign Information'));

        $this->addTab(
            'detail_section',
            [
                'label' => __('Campaign Information'),
                'content' => $this->getLayout()->createBlock('Magenest\Affiliate\Block\Adminhtml\Campaign\Edit\Tab\Main')->toHtml()
            ]
        );
        $this->addTab(
            'report_section',
            [
                'label' => __('Campaign Banner'),
                'content' => $this->getLayout()->createBlock('Magenest\Affiliate\Block\Adminhtml\Campaign\Edit\Tab\Secondary')->toHtml()
            ]
        );
    }
}
