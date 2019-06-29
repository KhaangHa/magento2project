<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 06/03/2018
 * Time: 13:00
 */
namespace Magenest\Affiliate\Model\Total\Quote;
use Magenest\Affiliate\Model\Customer;
use Magenest\Affiliate\Model\CustomerFactory;
use Magento\SalesRule\Model\Rule;

/**
 * Class Custom
 * @package Magenest\Affiliate\Model\Total\Quote
 */
class Affiliate extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;
    /**
     * @var \Magento\SalesRule\Model\CouponFactory
     */
    protected $couponFatory;
    /**
     * @var \Magenest\Affiliate\Cookie\Cookie
     */
    protected $cookie;
    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    protected $ruleFactory;
    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    protected $_configHelper;

    protected $_customerSession;

    protected $customerFactory;

    protected $customerRepository;
    /**
     * Custom constructor.
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\SalesRule\Model\CouponFactory $couponFactory,
        \Magenest\Affiliate\Cookie\Cookie $cookie,
        \Magento\SalesRule\Model\RuleFactory $ruleFactory,
        \Magento\Checkout\Model\Cart $cart,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magenest\Affiliate\Model\CustomerFactory $customerFactory,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository

    ){
        $this->_priceCurrency = $priceCurrency;
        $this->couponFatory = $couponFactory;
        $this->cookie = $cookie;
        $this->ruleFactory = $ruleFactory;
        $this->cart = $cart;
        $this->_configHelper = $configHelper;
        $this->_customerSession = $customerSession;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
    }
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|bool
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        if($this->checkAffiliateDiscount()) {
            $baseDiscount = $this->getAffiliateDiscount();
            $discount =  $this->_priceCurrency->convert($baseDiscount);
            $total->addTotalAmount('affiliatediscount', -$discount);
            $total->addBaseTotalAmount('affiliatediscount', -$baseDiscount);
            $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
            $quote->setCustomDiscount(-$discount);
            return $this;
        }

    }
    public function getAffiliateDiscount(){
        $cookie = $this->cookie->get();
        $discountAffiliate = 0;
        $discountAmount = $this->_configHelper->getAmountDiscount();
        $simpleActionRule = $this->_configHelper->getSimpleActionDiscount();
        $cart = $this->cart->getQuote()->getAllItems();
        if($simpleActionRule==Rule::BY_PERCENT_ACTION   ){
            foreach ($cart as $item){
                $productPrice = $item->getPrice();
                $productQty = $item->getQty();
                $discountAffiliate = $discountAffiliate+$productPrice*$productQty*$discountAmount/100;
            }
        } elseif ($simpleActionRule==Rule::BY_FIXED_ACTION) {
            $discountAffiliate = 0;
            foreach ($cart as $item){
                $productQty = $item->getQty();
                $discountAffiliate+=$productQty*$discountAmount;
            }
        } elseif ($simpleActionRule==Rule::CART_FIXED_ACTION) {
            $discountAffiliate = $discountAmount;
        }

        return $discountAffiliate;
    }

    public function checkAffiliateDiscount() {
        $coupon = $this->cookie->get();
        if($coupon){
            $customerAffiliate = $this->customerFactory->create()->getCollection()->addFieldToFilter('unique_code',$coupon)->getFirstItem();
            if($customerAffiliate){
                if($customerAffiliate['status']==Customer::STATUS_APPROVED)
                {
                    return true;
                }
            }
        }
        $customerId = $this->_customerSession->getCustomerId();
        if($customerId){
            $customerAffiliate = $this->customerRepository->getByCustomerId($customerId);
            if($customerAffiliate){
                if($customerAffiliate->getStatus()==Customer::STATUS_APPROVED){
                    return true;
                }
            }
        }
        return false;
    }
}