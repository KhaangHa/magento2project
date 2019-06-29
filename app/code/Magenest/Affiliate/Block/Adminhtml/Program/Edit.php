<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 16:02
 */
namespace Magenest\Affiliate\Block\Adminhtml\Program;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    protected $campaignFactory;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magenest\Affiliate\Model\Banner $campaignFactory,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->campaignFactory = $campaignFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve text for header element depending on loaded post
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('banner')->getId()) {
            return __("Program Information '%1'", $this->escapeHtml($this->_coreRegistry->registry('banner')->getType()));
        } else {
            return __('Program Information');

        }
    }

    /**
     * Initialize blog post edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Magenest_Affiliate';
        $this->_controller = 'adminhtml_program';

        parent::_construct();
        $this->buttonList->update('delete', 'label', __('Delete'));
        $this->buttonList->update('save', 'label', __('Save Banner'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
        $this->buttonList->update(
            'saveandcontinue',
            'onclick',
            'setLocation(\'' . $this->_getSaveAndContinueUrl() . '\')'
        );
//        if(!$this->getRequest()->getParam('id'))
//        {
//            $this->buttonList->remove('save');
//            $this->buttonList->remove('saveandcontinue');
//        }

        $this->buttonList->update(
            'save',
            'onclick',
            'setLocation(\'' . $this->_getSaveUrl() . '\')'
        );

    }
    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    /**
     * @return string
     */
    protected function _getSaveUrl()
    {
        return $this->getUrl('*/*/save');
    }


    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
                function toggleEditor() {
                    if (tinyMCE.getInstanceById('page_content') == null) {
                        tinyMCE.execCommand('mceAddControl', false, 'content');
                    } else {
                        tinyMCE.execCommand('mceRemoveControl', false, 'content');
                    }
                };
            ";
        $this->_formScripts[] .= '
                require(["prototype"], function(){
                    toggleParentVis("add_review_form");
                    toggleVis("save_button");
                    toggleVis("reset_button");
                });
            ';

        $this->_formInitScriptss[] = '
                require(["jquery","prototype"], function(jQuery){
                window.review = function() {
                    return {
                        productInfoUrl : null,
                        formHidden : true,
                        gridRowClick : function(data, click) {
                            if(Event.findElement(click,\'TR\').title){
                                review.productInfoUrl = Event.findElement(click,\'TR\').title;
                                review.loadProductData();
                                review.showForm();
                                review.formHidden = false;
                            }
                        },
                        
                        loadProductData : function() {
                            jQuery.ajax({
                                type: "POST",
                                url: review.productInfoUrl,
                                data: {
                                    form_key: FORM_KEY
                                },
                                showLoader: true,
                                success: review.reqSuccess,
                                error: review.reqFailure
                            });
                        },
                        showForm : function() {
                            toggleParentVis("add_review_form");
                            toggleVis("productGrid");
                            toggleVis("save_button");
                            toggleVis("reset_button");
                        },
                        updateRating: function() {
                            elements = [$("select_stores"), $("rating_detail").getElementsBySelector("input[type=\'radio\']")].flatten();
                            $(\'save_button\').disabled = true;
                            var params = Form.serializeElements(elements);
                            if (!params.isAjax) {
                                params.isAjax = "true";
                            }
                            if (!params.form_key) {
                                params.form_key = FORM_KEY;
                            }
                            new Ajax.Updater("rating_detail", "' .
            $this->getUrl(
                'review/product/ratingItems'
            ) .
            '", {parameters:params, evalScripts: true,  onComplete:function(){ $(\'save_button\').disabled = false; } });
                        },
    
                        reqSuccess :function(response) {
                            if( response.error ) {
                                alert(response.message);
                            } else if( response.id ){
                                $("product_id").value = response.id;
    
                                $("product_name").innerHTML = \'<a href="' .
            $this->getUrl(
                'catalog/product/edit'
            ) .
            'id/\' + response.id + \'" target="_blank">\' + response.name + \'</a>\';
                            } else if ( response.message ) {
                                alert(response.message);
                            }
                        }
                    }
                }();
                Event.observe(window, \'load\', function(){
                     if ($("select_stores")) {
                         Event.observe($("select_stores"), \'change\', review.updateRating);
                     }
                });
                });
               //]]>
            ';
        return parent::_prepareLayout();
    }
}
