<?php

namespace Magenest\Affiliate\Observer;

use Magento\Framework\Event\ObserverInterface;

class Param extends AffiliateObserver implements ObserverInterface
{
    protected $_customerSession;

    protected $customerInterfaceFactory;

    protected $customerRepository;

    public function __construct(
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magenest\Affiliate\Api\OrderRepositoryInterface $orderRepository,
        \Magenest\Affiliate\Api\Data\OrderInterfaceFactory $orderInterfaceFactory,
        \Magenest\Affiliate\Api\TransactionRepositoryInterface $transactionRepository,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->_customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->customerInterfaceFactory = $customerInterfaceFactory;
        parent::__construct($affiliateManagement, $orderRepository, $orderInterfaceFactory, $transactionRepository, $configHelper);
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $params = $observer->getRequest()->getParams();
        $customerId = $this->_customerSession->getCustomerId();
        if (isset($params['coupon_code'])) {
            $uplineCustomerByCoupon = $this->customerRepository->getByUniqueCode($params['coupon_code']);
            if ($uplineCustomerByCoupon && ($customerId !== $uplineCustomerByCoupon->getCustomerId())) {
                $hasUpline = ($customerId === null) ? false : $this->affiliateManagement->hasUpline($customerId);
                if ($hasUpline === false) {
                    $cookie = \Magento\Framework\App\ObjectManager::getInstance()->get('Magenest\Affiliate\Cookie\Cookie');
                    $cookie->set($uplineCustomerByCoupon->getCustomerId(), intval($this->configHelper->getCookieLifetime()));
                }
            }
        }
    }
}
