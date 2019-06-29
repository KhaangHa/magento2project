<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 03/02/2018
 * Time: 15:48
 */

namespace Magenest\Affiliate\Observer\Affiliate;

use Magenest\Affiliate\Model\AffiliateDiscount;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Config implements ObserverInterface
{
    /**
     * @var \Magenest\Affiliate\Model\AffiliateDiscountFactory
     */
    protected $affiliateDiscount;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\UrlRewrite\Model\UrlRewrite|\Magento\UrlRewrite\Model\UrlRewriteFactory
     */
    protected $_urlRewrite;

    /**
     * @var \Magento\Store\Model\Store
     */
    protected $store;

    /**
     * Config constructor.
     * @param \Magenest\Affiliate\Model\AffiliateDiscountFactory $affiliateDiscountFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Store\Model\Store $store
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewrite
     */
    public function __construct(
        \Magenest\Affiliate\Model\AffiliateDiscountFactory $affiliateDiscountFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\Store $store,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewrite
    ) {
        $this->affiliateDiscount = $affiliateDiscountFactory;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->_urlRewrite       = $urlRewrite;
        $this->store             = $store;
    }

    public function execute(Observer $observer)
    {
        $couponType = $this->scopeConfig->getValue("magenest_affiliate/discount/coupon_type");
        $discountValue = $this->scopeConfig->getValue("magenest_affiliate/discount/discount_value");
        $affiliateDiscount = $this->affiliateDiscount->create()->getCollection()->addFieldToFilter('type','config');
        foreach ($affiliateDiscount as $discount) {
            $discount->setSimpleAction($couponType);
            $discount->setDiscountAmount($discountValue);
            $discount->save();
        }
        $urlCoupon = $this->scopeConfig->getValue('magenest_affiliate/general/custom_param');
       $urlCouponFactory = $this->_urlRewrite->create()->getCollection()->addFieldToFilter('target_path','affiliate/coupon/apply');
       foreach ($urlCouponFactory as $url) {
           $url->setRequestPath($urlCoupon)->save();
       }
    }
}
