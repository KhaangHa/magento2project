<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 21/03/2018
 * Time: 10:11
 */
namespace Magenest\Affiliate\Model\Order\Invoice;

use Magento\Sales\Model\Order;
use Magenest\Affiliate\Helper\Constant;

class Totals extends \Magento\Sales\Model\Service\InvoiceService
{
    protected $orderRepository;
    protected $orderAffiliate;
    protected $configHelper;
    public function __construct(
        \Magento\Sales\Api\InvoiceRepositoryInterface $repository,
        \Magento\Sales\Api\InvoiceCommentRepositoryInterface $commentRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $criteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        Order\InvoiceNotifier $notifier,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Model\Convert\Order $orderConverter,
        \Magenest\Affiliate\Model\OrderFactory $orderAffiliate,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper
    ) {
        parent::__construct($repository, $commentRepository, $criteriaBuilder, $filterBuilder, $notifier, $orderRepository, $orderConverter);
        $this->orderAffiliate = $orderAffiliate;
        $this->configHelper= $configHelper;
    }
    public function setCapture($id)
    {
        return (bool)$this->repository->get($id)->capture();
    }

    /**
     * @inheritdoc
     */
    public function getCommentsList($id)
    {
        $this->criteriaBuilder->addFilters(
            [$this->filterBuilder->setField('parent_id')->setValue($id)->setConditionType('eq')->create()]
        );
        $searchCriteria = $this->criteriaBuilder->create();
        return $this->commentRepository->getList($searchCriteria);
    }

    /**
     * @inheritdoc
     */
    public function notify($id)
    {
        $invoice = $this->repository->get($id);
        return $this->invoiceNotifier->notify($invoice);
    }

    /**
     * @inheritdoc
     */
    public function setVoid($id)
    {
        return (bool)$this->repository->get($id)->void();
    }

    /**
     * @param Order $order
     * @param array $qtys
     * @return \Magento\Sales\Model\Order\Invoice
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function prepareInvoice(Order $order, array $qtys = [])
    {
        $invoice = $this->orderConverter->toInvoice($order);
        $totalQty = 0;
        $qtys = $this->prepareItemsQty($order, $qtys);
        $invoiceQty=[];
        foreach ($order->getAllItems() as $orderItem) {
            if (!$this->_canInvoiceItem($orderItem)) {
                continue;
            }
            $item = $this->orderConverter->itemToInvoiceItem($orderItem);
            if ($orderItem->isDummy()) {
                $qty = $orderItem->getQtyOrdered() ? $orderItem->getQtyOrdered() : 1;
            } elseif (isset($qtys[$orderItem->getId()])) {
                $qty = (double) $qtys[$orderItem->getId()];
            } elseif (empty($qtys)) {
                $qty = $orderItem->getQtyToInvoice();
            } else {
                $qty = 0;
            }
            $a = $orderItem->getItemId();
            $b = $orderItem->getId();
            $invoiceQty[$orderItem->getItemId()]=$qty;
            $totalQty += $qty;
            $this->setInvoiceItemQuantity($item, $qty);
            $invoice->addItem($item);
        }
        $invoice->setTotalQty($totalQty);
        $invoice->collectTotals();
        $invoice->setGrandTotal($invoice->getGrandTotal()-$this->getDiscountAffiliate($qtys,$invoiceQty,$order));
        $order->getInvoiceCollection()->addItem($invoice);
        return $invoice;
    }

    /**
     * Prepare qty to invoice for parent and child products if theirs qty is not specified in initial request.
     *
     * @param Order $order
     * @param array $qtys
     * @return array
     */
    private function prepareItemsQty(Order $order, array $qtys = [])
    {
        foreach ($order->getAllItems() as $orderItem) {
            if (empty($qtys[$orderItem->getId()])) {
                continue;
            }
            if ($orderItem->isDummy()) {
                if ($orderItem->getHasChildren()) {
                    foreach ($orderItem->getChildrenItems() as $child) {
                        if (!isset($qtys[$child->getId()])) {
                            $qtys[$child->getId()] = $child->getQtyToInvoice();
                        }
                    }
                } elseif ($orderItem->getParentItem()) {
                    $parent = $orderItem->getParentItem();
                    if (!isset($qtys[$parent->getId()])) {
                        $qtys[$parent->getId()] = $parent->getQtyToInvoice();
                    }
                }
            }
        }

        return $qtys;
    }

    /**
     * Check if order item can be invoiced. Dummy item can be invoiced or with his children or
     * with parent item which is included to invoice
     *
     * @param \Magento\Sales\Api\Data\OrderItemInterface $item
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _canInvoiceItem(\Magento\Sales\Api\Data\OrderItemInterface $item)
    {
        $qtys = [];
        if ($item->getLockedDoInvoice()) {
            return false;
        }
        if ($item->isDummy()) {
            if ($item->getHasChildren()) {
                foreach ($item->getChildrenItems() as $child) {
                    if (empty($qtys)) {
                        if ($child->getQtyToInvoice() > 0) {
                            return true;
                        }
                    } else {
                        if (isset($qtys[$child->getId()]) && $qtys[$child->getId()] > 0) {
                            return true;
                        }
                    }
                }
                return false;
            } elseif ($item->getParentItem()) {
                $parent = $item->getParentItem();
                if (empty($qtys)) {
                    return $parent->getQtyToInvoice() > 0;
                } else {
                    return isset($qtys[$parent->getId()]) && $qtys[$parent->getId()] > 0;
                }
            }
        } else {
            return $item->getQtyToInvoice() > 0;
        }
    }

    /**
     * Set quantity to invoice item
     *
     * @param \Magento\Sales\Api\Data\InvoiceItemInterface $item
     * @param float $qty
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function setInvoiceItemQuantity(\Magento\Sales\Api\Data\InvoiceItemInterface $item, $qty)
    {
        $qty = ($item->getOrderItem()->getIsQtyDecimal()) ? (double) $qty : (int) $qty;
        $qty = $qty > 0 ? $qty : 0;

        /**
         * Check qty availability
         */
        $qtyToInvoice = sprintf("%F", $item->getOrderItem()->getQtyToInvoice());
        $qty = sprintf("%F", $qty);
        if ($qty > $qtyToInvoice && !$item->getOrderItem()->isDummy()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('We found an invalid quantity to invoice item "%1".', $item->getName())
            );
        }

        $item->setQty($qty);

        return $this;
    }

    public function getDiscountAffiliate($qtys,$totalQty,$order){
        if($this->configHelper->isAffiliateEnable()) {
            if ($qtys) {
                $orderResponse = $this->orderAffiliate->create()->getCollection()->addFieldToFilter('order_id', $order->getId())->getFirstItem();
                if ($orderResponse) {
                    $data = unserialize($orderResponse['data']);
                    $data = $data['affiliate_discount'];
                    $totalInvoice = 0;
                    $total = 0;
                    foreach ($qtys as $key => $qty) {
                        $totalInvoice += $qty / $data[$key]['qty'] * $data[$key]['total'];
                        $total += $data[$key]['total'];
                    }
                    $discount = $data['total'] * $totalInvoice / $total;
                    return $discount;
                }

            } else {
                $orderResponse = $this->orderAffiliate->create()->getCollection()->addFieldToFilter('order_id', $order->getId())->getFirstItem();
                if ($orderResponse) {
                    $data = unserialize($orderResponse['data']);
                    $data = $data['affiliate_discount'];
                    $totalInvoice = 0;
                    $total = 0;
                    foreach ($totalQty as $key1 => $a) {
                        $totalInvoice += $a * $data[$key1]['total'] / $data[$key1]['qty'];
                    }
                    foreach ($data as $totalOrder) {
                        $total += $totalOrder['total'];
                    }
                    $discount = $totalInvoice * $data['total'] / $total;
                    return $discount;
                }

            }
        }

            return 0;
    }
}