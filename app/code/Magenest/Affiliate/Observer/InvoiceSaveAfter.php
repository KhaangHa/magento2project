<?php

namespace Magenest\Affiliate\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magenest\Affiliate\Model\System\Config\Source\CommissionType;

class InvoiceSaveAfter extends AffiliateObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //3
        /**
 * @var \Magento\Sales\Model\Order\Invoice $invoice
*/
        if($this->configHelper->isAffiliateEnable()) {


            $invoice = $observer->getData('invoice');
            $isRefund = $invoice->getIsUsedForRefund();
            if ($isRefund) {
                return;
            }
            $order = $invoice->getOrder();
            $orderId = $order->getIncrementId();
            $invoiceId = $invoice->getIncrementId();
            $orderModel = $this->orderRepository->getByOrderId($orderId);
            $commissionCondition = $orderModel->getOrderData('commission_condition');
            $commissionHoldDay = $orderModel->getCommissionHold();
            $commissionType = $orderModel->getOrderData('commission_type');
            $commissionTypeValue = $orderModel->getCommissionValue();
            $items = $invoice->getItems();
            $totalQty = 0;
            $customerId = $order->getCustomerId();
            foreach ($items as $item) {
                $totalQty += $item->getQty();
            }
            $commissonMoney = $this->calculateCommissionMoney($invoice, $commissionType, $commissionTypeValue, $totalQty);
            if ($commissionCondition == Order::STATE_PROCESSING) {
                $this->affiliateManagement->addCommissionTransaction($customerId, $commissonMoney, $orderId, $invoiceId, $commissionHoldDay);
            }
//                if($commissionCondition == Order::STATE_COMPLETE){
//                    $orderState = $order->getState();
//                    if($orderState == Order::STATE_COMPLETE) {
//                        $this->affiliateManagement->addCommissionTransaction($customerId, $commissonMoney, $orderId, $invoiceId, $commissionHoldDay);
//                    }
//                }
        }

    }

    /**
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @param $commissionType
     * @param float                              $commissionTypeValue
     * @return float;
     */
    private function calculateCommissionMoney($invoice, $commissionType, $commissionTypeValue, $totalQty)
    {
        $baseTotal = $invoice->getBaseGrandTotal();
        $money = 0;
        if ($commissionType == CommissionType::COMMISSION_PERCENTAGE) {
            $money = $baseTotal*$commissionTypeValue/100;
            return $money;
        }
        if ($commissionType == CommissionType::COMMISSION_FIXED_PER_ITEM) {
            $items = $invoice->getItems();
            foreach ($items as $item) {
                $money += $item->getQty()*$commissionTypeValue;
            }
        }
        if ($commissionType == CommissionType::COMMISSION_FIXED_PER_CART) {
            $qty = 0;
            $items = $invoice->getItems();
            foreach ($items as $item) {
                $qty += $item->getQty();
            }

            $money += $commissionTypeValue*($qty/$totalQty);
        }
        return $money;
    }
}
