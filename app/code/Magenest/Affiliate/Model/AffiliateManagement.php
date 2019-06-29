<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 05/08/2017
 * Time: 13:38
 */

namespace Magenest\Affiliate\Model;

use Magenest\Affiliate\Helper\Constant;

class AffiliateManagement
{
    protected $customerInterfaceFactory;
    protected $customerRepository;
    protected $downlineInterfaceFactory;
    protected $downlineRepository;
    protected $transactionInterfaceFactory;
    protected $transactionRepository;
    protected $configHelper;
    protected $orderRepository;
    protected $eventManager;
    public function __construct(
        \Magenest\Affiliate\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Api\Data\DownlineInterfaceFactory $downlineInterfaceFactory,
        \Magenest\Affiliate\Api\DownlineRepositoryInterface $downlineRepository,
        \Magenest\Affiliate\Api\Data\TransactionInterfaceFactory $transactionInterfaceFactory,
        \Magenest\Affiliate\Api\TransactionRepositoryInterface $transactionRepository,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Event\Manager $eventManager
    ) {
        $this->customerInterfaceFactory = $customerInterfaceFactory;
        $this->customerRepository = $customerRepository;
        $this->downlineRepository = $downlineRepository;
        $this->downlineInterfaceFactory = $downlineInterfaceFactory;
        $this->transactionInterfaceFactory = $transactionInterfaceFactory;
        $this->transactionRepository = $transactionRepository;
        $this->configHelper = $configHelper;
        $this->orderRepository = $orderRepository;
        $this->eventManager = $eventManager;
    }

    /**
     * @param $customerId
     * @param null $customerIdUpline
     * @param int $status
     */
    public function joinAffiliate($customerId, $customerIdUpline = null, $status = Constant::AFFILIATE_CUSTOMER_PENDING)
    {
        $customer = $this->customerRepository->getByCustomerId($customerId);

//        Save affiliate log for customer
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $customerInfo = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
        $description = $customerInfo->getEmail() . " wants to join Affiliate program";
        $log = $objectManager->create('Magenest\Affiliate\Model\Log');
        $log->setType(1);
        $log->setDescription($description);
        $log->save();

      if (!$customer) {
            $customerUpline = $this->customerRepository->getByCustomerId($customerIdUpline);
            if ($customerUpline) {
                if ($customerUpline->getStatus()!=Constant::AFFILIATE_CUSTOMER_APPROVED) {
                    $customerIdUpline = null;
                }
            }
            $customer = $this->customerInterfaceFactory->create();
            $customer->setCustomerId($customerId);
            $customer->setStatus($status);
            $customer->setUniqueCode($this->configHelper->getUniqueCode($customerId,$customer->getCreatedTime()));
            $this->customerRepository->save($customer);

            $downlineCustomer = $this->downlineRepository->getByCustomerDownline($customerId);
            if (!$downlineCustomer) {
                $downlineCustomer = $this->downlineInterfaceFactory->create();
                $downlineCustomer->setData("customer_id_downline", $customerId);
                $downlineCustomer->setData("customer_id_upline", $customerIdUpline);
            } else {
                $downlineCustomer->setData("customer_id_upline", $customerIdUpline);
            }
            $this->downlineRepository->save($downlineCustomer);

            //Save log





        }
    }

    public function addCommissionTransaction($customerId, $money, $orderId, $invoiceId, $holdDay)
    {
        if ($this->configHelper->isCommissionEnable()) {
            if ($money > 0) {
                $order = $this->orderRepository->getByOrderId($orderId);
                $uplineCustomerId = $order->getUplineCustomerId();
                if (!!$uplineCustomerId) {
                    $transaction = $this->transactionInterfaceFactory->create();
                    $transaction->setData('customer_id_upline', $uplineCustomerId);
                    $transaction->setData('customer_id_downline', $customerId);
                    $transaction->setData('order_id', $orderId);
                    $transaction->setData('invoice_id', $invoiceId);
                    $transaction->setData('creditmemo_id', null);
                    $transaction->setData('count_down', $holdDay);
                    $transaction->setData('receive_money', $money);
                    $transaction->setData('description', $this->getCommissionDescription($orderId));
                    $this->transactionRepository->save($transaction);
                    $this->eventManager->dispatch('magenest_affiliate_update_balance', ['customer_id'=>$uplineCustomerId]);
                }
            }
        }
    }

    public function addSubtractCommissionTransaction($customerId, $money, $orderId, $invoiceId, $memoId)
    {
        if ($this->configHelper->isCommissionEnable()) {
            if ($money>0) {
                $order = $this->orderRepository->getByOrderId($orderId);
                $uplineCustomerId = $order->getUplineCustomerId();
                if (!!$uplineCustomerId) {
                    $transaction = $this->transactionInterfaceFactory->create();
                    $transaction->setData('customer_id_upline', $uplineCustomerId);
                    $transaction->setData('customer_id_downline', $customerId);
                    $transaction->setData('order_id', $orderId);
                    $transaction->setData('invoice_id', $invoiceId);
                    $transaction->setData('creditmemo_id', $memoId);
                    $transaction->setData('count_down', "0");
                    $transaction->setData('subtract_money', $money);
                    $transaction->setData('description', $this->getSubtractCommissionDescription($orderId));
                    $this->transactionRepository->save($transaction);
                    $this->eventManager->dispatch('magenest_affiliate_update_balance', ['customer_id'=>$uplineCustomerId]);
                }
            }
        }
    }

    //    public function hasUpline($customerId){
    //        $downlineCustomer = $this->downlineRepository->getByCustomerDownline($customerId);
    //        if(!$downlineCustomer->getData('customer_id_upline')){
    //            return false;
    //        }else{
    //            return true;
    //        }
    //    }

    private function getCommissionDescription($orderId)
    {
        return "Commission for order #".$orderId;
    }

    private function getSubtractCommissionDescription($orderId)
    {
        return "Refund for order #".$orderId;
    }
}
