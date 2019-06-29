<?php

namespace Magenest\Affiliate\Block\Adminhtml\Payment;

/**
 * Grid Grid
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Edit constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry           $coreRegistry
     * @param array                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_objectId = 'withdraw_id';
        $this->_blockGroup = 'Magenest_Affiliate';
        $this->_controller = 'adminhtml_payment';

        parent::_construct();
        $this->buttonList->remove("reset");
        //        $this->buttonList->update('save', 'label', __('Save Payment'));
        //        $this->buttonList->update('delete', 'label', __('Delete'));
        //
        //        $data = $this->getRegistryModel();
        //
        //        if (isset($data['status']) && ($data['status'] == 3 || $data['status'] == 4)) {
        //            $this->removeButton('delete');
        //        } else {
        //            if (!$this->getRequest()->getParam('payment_id')) {
        //                $this->buttonList->add(
        //                    'saveandcontinue',
        //                    [
        //                        'label' => __('Save and Pay Manually'),
        //                        'class' => 'save',
        //                        'data_attribute' => [
        //                            'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
        //                        ],
        //                    ],
        //                    -200
        //                );
        //            } else {
        //                $this->removeButton('delete');
        //            }
        //
        //            if (isset($data['status']) && $data['status'] && $data['status'] < 3) {
        //                $this->buttonList->add(
        //                    'cancel-payment',
        //                    [
        //                        'label' => __('Cancel'),
        //                        'class' => 'cancel',
        //                        'data_attribute' => [
        //                            'mage-init' => [
        //                                'button' => ['event' => 'cancelPayment', 'target' => '#edit_form'],
        //                            ],
        //                        ],
        //                    ],
        //                    -200
        //                );
        //
        //                $this->buttonList->add(
        //                    'complete-payment-manually',
        //                    [
        //                        'label' => __('Complete Manually'),
        //                        'class' => 'save',
        //                        'data_attribute' => [
        //                            'mage-init' => [
        //                                'button' => ['event' => 'completePayment', 'target' => '#edit_form'],
        //                            ],
        //                        ],
        //                    ],
        //                    -150
        //                );
        //            }
        //
        //            $reviewUrl = $this->getUrl('affiliateplusadmin/payment/review',
        //                [
        //                    'payment_id' => $this->getRequest()->getParam('payment_id'),
        //                    'store' => $this->getRequest()->getParam('store')
        //                ]
        //            );
        //
        //            $cancelurl = $this->getUrl('affiliateplusadmin/payment/cancelPayment',
        //                [
        //                    'payment_id' => $this->getRequest()->getParam('payment_id'),
        //                    'store' => $this->getRequest()->getParam('store')
        //                ]
        //            );
        //
        //        }

        return;
    }

    /**
     * get registry model.
     *
     * @return \Magento\Framework\Model\AbstractModel|null
     */
    public function getRegistryModel()
    {
        return $this->_coreRegistry->registry('payment_data');
    }

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->getRegistryModel()->getId()) {
            return __("Edit Withdrawal '%1'", $this->escapeHtml($this->getRegistryModel()->getAccountName()));
        } else {
            return __('Create New Withdrawal');
        }
    }
}
