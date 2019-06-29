<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 07/10/2016
 * Time: 16:42
 */

namespace Magenest\Affiliate\Block\Adminhtml\Transaction\Edit;

/**
 * Class Form
 *
 * @package Magenest\MultipleVendor\Block\Adminhtml\Question\Edit
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
