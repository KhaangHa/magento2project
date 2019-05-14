<?php
namespace Magenest\Movie\Block\Adminhtml\Model;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ListField extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $_template = 'Magenest_Movie::system/config/list.phtml';

    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
    public function getSelectHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'select_field',
                'value' => '1', 'label' => __('Show'),
                'value' => '2', 'label' => __('Not Show')
            ]
        );

        return $button->toHtml();
    }
    /**
     * Generate collect button html
     *
     * @return string
     */

}
