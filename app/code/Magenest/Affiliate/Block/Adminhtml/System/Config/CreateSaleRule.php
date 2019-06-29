<?php
/**
 * Assembly_Payments extension
 *
 * @package   Assembly_Payments
 * @copyright Copyright (c) 2017 Assembly Payments (https://assemblypayments.com/)
 */

namespace Magenest\Affiliate\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field as FormField;
use Magento\Framework\Data\Form\Element\AbstractElement;
use \Magento\Backend\Block\Template;

class CreateSaleRule extends FormField
{
    protected $_config;

    protected $storeManager;

    public function __construct(
        Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        array $data = []
    ) {
        $this->_config = $configHelper;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/create-sale-rule.phtml');
        }

        return $this;
    }

    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    /**
     * Get the button and scripts contents
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $check = $this->_config->isSaleRuleCreated();
        if ($check) {
            $buttonLabel = "Sale rule was created";
            $class = "sale-rule-created";
        } else {
            $buttonLabel = "Create Sale Rule";
            $class = "";
        }

        $this->addData(
            [
                'edit_link' => $this->_urlBuilder->getUrl('sales_rule/promo_quote/edit/id/'.$this->_config->getRuleId()),
                'add_class' => __($class),
                'rule_id' => $this->_config->getRuleId(),
                'button_label' => __($buttonLabel),
                'html_id' => $element->getHtmlId(),
                'ajax_url' => $this->_urlBuilder->getUrl('affiliate/system_config/createSaleRule'),
            ]
        );

        return $this->_toHtml();
    }
}
