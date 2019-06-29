<?php

namespace Magenest\Affiliate\Observer;

use Magenest\Affiliate\Block\Adminhtml\AffiliateDiscount;
use Magenest\Affiliate\Helper\Constant;
use Magenest\Affiliate\Model\Customer;
use Magenest\Affiliate\Model\CustomerFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magenest\Affiliate\Model\System\Config\Source\CommissionType;

class SalesOrderSaveAfter extends AffiliateObserver implements ObserverInterface
{
    protected $customerRepository;
    protected $eventManager;
    protected $downlineRepository;
    protected $customerAffiliateFactory;
    protected $couponCookie;
    protected $_configHelper;
    protected $cart;

    public function __construct(
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Model\CustomerFactory $customerAffiliate,
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magenest\Affiliate\Api\OrderRepositoryInterface $orderRepository,
        \Magenest\Affiliate\Api\Data\OrderInterfaceFactory $orderInterfaceFactory,
        \Magenest\Affiliate\Api\TransactionRepositoryInterface $transactionRepository,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magento\Framework\Event\Manager $eventManager,
        \Magenest\Affiliate\Api\DownlineRepositoryInterface $downlineRepository,
        \Magenest\Affiliate\Cookie\Cookie $couponCookie,
        \Magento\Checkout\Model\Cart $cart


    ) {
        $this->customerRepository = $customerRepository;
        parent::__construct($affiliateManagement, $orderRepository, $orderInterfaceFactory, $transactionRepository, $configHelper);
        $this->eventManager = $eventManager;
        $this->downlineRepository = $downlineRepository;
        $this->customerAffiliateFactory = $customerAffiliate;
        $this->couponCookie = $couponCookie;
        $this->configHelper = $configHelper;
        $this->cart = $cart;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //3
        /**
 * @var \Magento\Sales\Model\Order $order
*/
        $order = $observer->getData('order');
        $customerId = $order->getCustomerId();
        $uplineCustomerId = $this->downlineRepository->findUplineCustomerId($customerId);
        $coupon = $order->getCouponCode();
        if ($coupon) {
            $customerUpline = $this->customerRepository->getByUniqueCode($coupon);
            if ($customerUpline) {
                if ($customerUpline->getStatus() == Constant::AFFILIATE_CUSTOMER_APPROVED) {
                    //todo
                    if ($customerUpline->getCustomerId() != $customerId) {
                        $uplineCustomerId = $customerUpline->getCustomerId();
                    }
                    //set cookie
                    $this->eventManager->dispatch(
                        'magenest_affiliate_set_cookie',
                        [
                        'unique_code' => $coupon
                        ]
                    );
                }
            }
        }
        $orderId = $order->getIncrementId();
        $orderModel = $this->orderRepository->getByOrderId($orderId);
        //check have coupon or is affiliate
        if($this->checkCookieCoupon()||$this->checkCustomerAffilite($customerId)) {
            if (!$orderModel) {
                $orderModel = $this->orderInterfaceFactory->create();
                $orderModel->setOrderId($orderId);
                $orderModel->setOrderData("upline_customer_id", $uplineCustomerId);
                $orderModel->setOrderData("commission_condition", $this->configHelper->getCommissionCondition());
                $orderModel->setOrderData("commission_type", $this->configHelper->getCommissionType());
                $orderModel->setOrderData("commission_value", $this->configHelper->getCommissionValue());
                $orderModel->setOrderData("commission_hold", $this->configHelper->getCommissionHold());
                $orderModel->setOrderData("subtract_commission", $this->configHelper->isSubtractCommission());
                $orderModel->setOrderData("subtract_commission_type", $this->configHelper->getSubtractCommissionType());
                $orderModel->setOrderData("subtract_commission_value", $this->configHelper->getSubtractCommissionValue());
                $orderModel->setOrderData("affiliate_discount", $this->getAffiliateDiscount($order));

                $this->orderRepository->save($orderModel);
            }

            $commissionCondition = $orderModel->getOrderData('commission_condition');
            $isCaptureFinal = $orderModel->getOrderData('is_capture_final');
            if (!$isCaptureFinal) {
                if ($commissionCondition == Order::STATE_COMPLETE) {
                    $orderState = $order->getState();
                    if ($orderState == Order::STATE_COMPLETE) {
                        $orderModel->setOrderData("is_capture_final", true);
                        $this->orderRepository->save($orderModel);
                        $commissionType = $orderModel->getOrderData('commission_type');
                        $commissionTypeValue = $orderModel->getCommissionValue();
                        $commissonMoney = $this->calculateCommissionMoney($order, $commissionType, $commissionTypeValue);
                        $invoice = $order->getInvoiceCollection()->getFirstItem();
                        $invoiceId = $invoice->getIncrementId() ?: null;
                        $customerId = $order->getCustomerId();
                        $commissionHoldDay = $orderModel->getCommissionHold();
                        $this->affiliateManagement->addCommissionTransaction(
                            $customerId,
                            $commissonMoney,
                            $orderId,
                            $invoiceId,
                            $commissionHoldDay
                        );
                    }
                }
            }
        }

    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @param $commissionType
     * @param $commissionTypeValue
     * @return float|int
     */
    private function calculateCommissionMoney($order, $commissionType, $commissionTypeValue)
    {
        $baseTotal = $order->getBaseGrandTotal();
        $money = 0;
        if ($commissionType == CommissionType::COMMISSION_PERCENTAGE) {
            $money = $baseTotal * $commissionTypeValue / 100;
            return $money;
        }
        if ($commissionType == CommissionType::COMMISSION_FIXED_PER_ITEM) {
            $items = $order->getItems();
            foreach ($items as $item) {
                $money += $item->getQty() * $commissionTypeValue;
            }
        }
        if ($commissionType == CommissionType::COMMISSION_FIXED_PER_CART) {
            $money += $commissionTypeValue;
        }
        return $money;
    }

    public function checkCustomerAffilite($customerId){
        $customer = $this->customerAffiliateFactory->create()->getCollection()
                        ->addFieldToFilter('customer_id',$customerId)->getFirstItem();
        if($customer){
            $status = $customer['status'];
            if($status==Customer::STATUS_APPROVED) {
                return true;
            }
        }
        return false;
    }

    public function checkCookieCoupon(){
        if($this->couponCookie->get()){
            return true;
        }
        return false;
    }

    public function getAffiliateDiscount($order){
        $data =[];
        $total=0;
        $orderItems = $order->getAllItems();
        foreach ($orderItems as $orderItem) {
            $orderItemId = $orderItem->getItemId();
            $data[$orderItem->getItemId()]['qty']=$orderItem->getQtyToInvoice();
            $data[$orderItem->getItemId()]['total']= $orderItem->getRowTotal();
        }
//        $total = $this->getAffiliateDiscount($order);
        $data['total']=$total;
        return $data;
    }
}
