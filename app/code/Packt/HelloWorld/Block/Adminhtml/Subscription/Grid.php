<?php

namespace Packt\HelloWorld\Block\Adminhtml\Subscription;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid as WidgetGrid;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Packt\HelloWorld\Model\ResourceModel\Subscription\Collection;

class Grid extends Extended
{
    /**
     * @var \Packt\HelloWorld\Model\Resource\Subscription\
     * Collection
     */
    protected $_subscriptionCollection;

    /**
     * @param Context
     * $context
     * @param Data $backendHelper
     * @param
     * Subscription\Collection $subscriptionCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        Collection $subscriptionCollection,
        array $data = []
    )
    {
        $this->_subscriptionCollection = $subscriptionCollection;
        parent::__construct($context, $backendHelper, $data);
        $this->setEmptyText(__('No Subscriptions Found'));
    }
    public function decorateStatus($value) {
        $class = '';
        switch ($value) {
            case 'pending':
                $class = 'grid-severity-minor';
                break;
            case 'approved':
                $class = 'grid-severity-notice';
                break;
            case 'declined':
            default:
                $class = 'grid-severity-critical';
                break;
        }
        return '<span class="' . $class . '"><span>' . $value . '</span></span>';
    }

    /**
     * Initialize the subscription collection
     *
     * @return WidgetGrid
     */
    protected function _prepareCollection()
    {
        $this->setCollection($this->_subscriptionCollection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'subscription_id',
            [

                'header' => __('ID'),
                'index' => 'subscription_id',
            ]
        );
        $this->addColumn(
            'firstname',
            [
                'header' => __('Firstname'),
                'index' => 'firstname',
            ]
        );
        $this->addColumn(
            'lastname',
            [
                'header' => __('Lastname'),
                'index' => 'lastname',
            ]
        );
        $this->addColumn(
            'email',
            [
                'header' => __('Email address'),
                'index' => 'email',
            ]
        );
        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'frame_callback' => [$this, 'decorateStatus']
            ]
        );
        return $this;
    }
}