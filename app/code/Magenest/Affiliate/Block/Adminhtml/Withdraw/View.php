<?php
/**
 * Created by Magenest.
 * Author: Pham Quang Hau
 * Date: 12/08/2016
 * Time: 23:50
 */

namespace Magenest\Affiliate\Block\Adminhtml\Withdraw;

class View extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $coreRegistry;
    protected $orderFactory;
    protected $withdrawRepository;
    protected $customerInterfaceFactory;
    protected $customerRepository;
    public $id;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magenest\Affiliate\Api\WithdrawRepositoryInterface $withdrawRepository,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory,
        array $data
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerInterfaceFactory = $customerInterfaceFactory;
        $this->withdrawRepository = $withdrawRepository;
        $this->coreRegistry = $registry;
        $this->orderFactory = $orderFactory;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'withdraw_id';
        $this->_blockGroup = 'Magenest_Affiliate';
        $this->_controller = 'adminhtml_payment';

        parent::_construct();

        $withdraw = $this->getWithdraw();
        if ($withdraw['status'] == '0') {
            $this->buttonList->add(
                'approve',
                [
                    'label' => __('Capture Payment'),
                    'class' => 'action primary approve-withdraw'
                ],
                0
            );
            $this->buttonList->add(
                'decline',
                [
                    'label' => __('Decline Payment'),
                    'onclick' => 'decline',
                    'class' => 'action secondary'
                ],
                0
            );
        }
        $this->removeButton('save');
        $this->removeButton('reset');
        return;
    }

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('View Withdrawal');
    }

    public function getWithdraw()
    {
        $id = $this->coreRegistry->registry('affiliate_withdraw_id');
        $this->id = $id;
        $withdraw = $this->withdrawRepository->getById($id);
        if ($withdraw) {
            return $withdraw->getData();
        }
        return false;
    }

    public function getCustomer($customerId)
    {
        $customer = $this->customerRepository->getByCustomerId($customerId);
        if ($customer) {
            return $customer->getData();
        }
        return false;
    }

    public function getNameFromId($customerId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /**
         * @var \Magento\Customer\Api\Data\CustomerInterface $customer
         */
        $customer = $objectManager->create('Magento\Customer\Api\CustomerRepositoryInterface')->getById($customerId);
        return $customer->getFirstname() . ' ' . $customer->getLastname();
    }
}
