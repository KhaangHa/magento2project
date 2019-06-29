<?php

namespace Magenest\Affiliate\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magenest\Affiliate\Model\System\Config\Source\SubtractCommissionType;

class CreditmemoSaveAfter extends AffiliateObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**
 * @var \Magento\Sales\Model\Order\Creditmemo $creditmemo
*/
        $creditmemo = $observer->getData('creditmemo');
        $order = $creditmemo->getOrder();
        $orderId = $order->getIncrementId();
        $invoice = $creditmemo->getInvoice();
        $invoiceId = (!!$invoice)?$invoice->getIncrementId():null;
        $creditmemoId = $creditmemo->getIncrementId();
        $orderModel = $this->orderRepository->getByOrderId($orderId);
        $subtractCondition = $orderModel->getOrderData('subtract_commission');
        $subtractConditionType = $orderModel->getOrderData('subtract_commission_type');
        $subtractConditionTypeValue = $orderModel->getSubtractValue();
        $customerId = $order->getCustomerId();
        if ($subtractCondition) {
            $subtractMoney = $this->calculateSubtractMoney($creditmemo, $subtractConditionType, $subtractConditionTypeValue);
            $this->affiliateManagement->addSubtractCommissionTransaction($customerId, $subtractMoney, $orderId, $invoiceId, $creditmemoId);
        }
    }

    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @param $subtractType
     * @param $subtractTypeValue
     * @return float
     */
    private function calculateSubtractMoney($creditmemo, $subtractType, $subtractTypeValue)
    {
        $subtractMoney = 0;
        $order = $creditmemo->getOrder();
        $orderId = $order->getIncrementId();
        $totalInvoice = $order->getTotalInvoiced();
        $totalCommission = $this->transactionRepository->getTotalCommissionByOrderId($orderId);
        $totalRefund = $creditmemo->getBaseGrandTotal();
        $refundRatio = $totalRefund/$totalInvoice;
        $fullCommissionSubtract = $refundRatio*$totalCommission;
        if ($subtractType == SubtractCommissionType::SUBTRACT_PERCENTAGE) {
            $finalCommissionSubtract = $fullCommissionSubtract*$subtractTypeValue/100;
            $subtractMoney+=$finalCommissionSubtract;
        }
        return $subtractMoney;
    }
}
