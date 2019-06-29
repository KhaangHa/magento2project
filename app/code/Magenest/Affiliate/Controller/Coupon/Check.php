<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 07/03/2018
 * Time: 09:01
 */

namespace Magenest\Affiliate\Controller\Coupon;

use Magenest\Affiliate\Helper\Constant;
use Magenest\Affiliate\Model\Customer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\SalesRule\Model\Rule;

class Check extends Action
{
    protected $_customerSession;
    protected $_pageFactory;
    protected $jsonFactory;
    protected $_configHelper;
    protected $affiliateManagement;
    protected $resultRedirect;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $_storeManager;

    protected $customerRepository;

    protected $cookie;

    protected $logger;

    protected $customerFactory;

    protected $discountAffiliate;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Controller\ResultFactory $result,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Cookie\Cookie $cookie,
        \Magento\Checkout\Model\Cart $cart,
        \Psr\Log\LoggerInterface $_logger,
        \Magenest\Affiliate\Model\CustomerFactory  $customerFactory,
        \Magenest\Affiliate\Model\AffiliateDiscountFactory $affiliateDiscountFactory




    ) {
        parent::__construct($context);
        $this->jsonFactory = $resultJsonFactory;
        $this->_customerSession = $customerSession;
        $this->_pageFactory = $pageFactory;
        $this->_configHelper = $configHelper;
        $this->affiliateManagement = $affiliateManagement;
        $this->_storeManager = $storeManager;
        $this->resultRedirect = $result;
        $this->customerRepository = $customerRepository;
        $this->cookie = $cookie;
        $this->cart = $cart;
        $this->logger = $_logger;
        $this->customerFactory = $customerFactory;
        $this->discountAffiliate = $affiliateDiscountFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $data=['discount'=>null];
        $result = $this->jsonFactory->create();
        if($this->checkAffiliateDiscount()) {
            $discount = $this->getAffiliateDiscount();
            if($discount)
            {
                $data = [
                    'discount'=> $discount
                ];
            }
            return $result->setData($data);
        }
        return $result->setData($data);
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
