<?php

namespace Magenest\Affiliate\Block\Adminhtml\Transaction\Edit\Tab;

/**
 * Class Main
 * @package Magenest\Affiliate\Campaign\Block\Adminhtml\Campaign\Edit\Tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    /**
     * @var string
     */
    protected $_template = 'transaction/edit.phtml';

    /**
     * @var \Magenest\Affiliate\Model\CampaignFactory
     */
    protected $campaignFactory;


    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magenest\Affiliate\Model\TransactionFactory $campaignFactory,
        array $data = []

    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->campaignFactory = $campaignFactory;
    }

    /**
     * Return Tab label
     *
     * @return string
     * @api
     */
    public function getTabLabel()
    {
        return __('Transaction Information');

    }


    /**
     * Return Tab title
     *
     * @return string
     * @api
     */
    public function getTabTitle()
    {
        return __('Transaction Information');

    }


    /**
     * Can show tab in tabs
     *
     * @return boolean
     * @api
     */
    public function canShowTab()
    {
        return true;

    }


    /**
     * Tab is hidden
     *
     * @return boolean
     * @api
     */
    public function isHidden()
    {
        return false;

    }

    /**
     * Get Auth Answer
     *
     * @return mixed|string
     */
    public function getAuthorAnswer()
    {
        return $this->authSession->getUser()->getUserName();
    }

    public function getId(){
        $id = $this->getRequest()->getParam('id');
        if($id){
            return $id;
        }
        return null;
    }

    public function getTransaction(){
        $id = $this->getId();
        if($id){
            $campaign = $this->campaignFactory->create()->load($id)->getData();
            return $campaign;
        }
        return null;
    }

}
