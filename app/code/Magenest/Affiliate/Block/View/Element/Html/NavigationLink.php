<?php

namespace Magenest\Affiliate\Block\View\Element\Html;

use Magenest\Affiliate\Helper\Constant;
use Magento\Framework\View\Element\Template;

class NavigationLink extends \Magento\Framework\View\Element\Html\Link
{
    protected $_customerSession;
    protected $customerRepository;
    protected $_configHelper;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        Template\Context $context,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        array $data = []
    ) {
        $this->_configHelper = $configHelper;
        parent::__construct($context, $data);
        $this->_customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
    }

    public function getIsAffiliate()
    {
        try {
            $customerId = $this->_customerSession->getCustomerId();
            $customer = $this->customerRepository->getByCustomerId($customerId);
            if (!$customer || $customer->getStatus() != Constant::AFFILIATE_CUSTOMER_APPROVED) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function getBalance()
    {
        $customerId = $this->_customerSession->getCustomerId();
        $customer = $this->customerRepository->getByCustomerId($customerId);
        $balance = 0;
        if ($customer) {
            $balance = $customer->getBalance();
        }
        return $balance;
    }

    public function getCustomer()
    {
        $id = $this->_customerSession->getCustomerId();
        return $this->customerRepository->getByCustomerId($id);
    }

    public function getBaseCurrencySymbol()
    {
        return $this->_configHelper->getBaseCurrencySymbol();
    }

    public function getWithdrawMethod()
    {
        return $this->_configHelper->getAllowedMethods();
    }
    public function getMinWithdraw()
    {
        return $this->_configHelper->getMinWithdraw();
    }

    public function getMaxWithdraw()
    {
        return $this->_configHelper->getMaxWithdraw();
    }
}
