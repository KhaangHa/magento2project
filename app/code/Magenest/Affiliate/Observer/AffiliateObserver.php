<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 07/08/2017
 * Time: 08:27
 */

namespace Magenest\Affiliate\Observer;

use Magento\Framework\Event\ObserverInterface;

abstract class AffiliateObserver implements ObserverInterface
{
    protected $affiliateManagement;
    protected $orderRepository;
    protected $orderInterfaceFactory;
    protected $transactionRepository;
    protected $configHelper;

    public function __construct(
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magenest\Affiliate\Api\OrderRepositoryInterface $orderRepository,
        \Magenest\Affiliate\Api\Data\OrderInterfaceFactory $orderInterfaceFactory,
        \Magenest\Affiliate\Api\TransactionRepositoryInterface $transactionRepository,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper
    ) {
        $this->affiliateManagement = $affiliateManagement;
        $this->orderRepository = $orderRepository;
        $this->orderInterfaceFactory = $orderInterfaceFactory;
        $this->transactionRepository = $transactionRepository;
        $this->configHelper = $configHelper;
    }

    /**
     * @param \Magenest\Affiliate\Api\Data\OrderInterface $orderModel
     * @return array
     */
    protected function getAffiliateOption($orderModel)
    {
        if ($orderModel) {
            return $orderModel->getOrderData();
        } else {
            return [
                'commission_condition' => $this->configHelper->getCommissionCondition(),
                'commission_type' => $this->configHelper->getCommissionType(),
                'commission_value' => $this->configHelper->getCommissionValue(),
                'commission_hold' => $this->configHelper->getCommissionHold(),
                'subtract_commission' => $this->configHelper->isSubtractCommission(),
                'subtract_commission_type' => $this->configHelper->getSubtractCommissionType(),
                'subtract_commission_value' => $this->configHelper->getSubtractCommissionValue()
            ];
        }
    }
}
