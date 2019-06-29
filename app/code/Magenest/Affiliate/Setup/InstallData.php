<?php

namespace Magenest\Affiliate\Setup;

use Magenest\Affiliate\Block\AffiliateDiscount;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{

    /**
     * @var \Magento\UrlRewrite\Model\UrlRewrite
     */
    protected $_urlRewrite;

    /**
     * @var \Magento\Store\Model\Store
     */
    protected $store;
    /**
     * @var \Magenest\Affiliate\Model\AffiliateDiscountFactory
     */
    protected $discountAffiliateFactory;


    public function __construct(
        \Magento\Framework\App\State $state,
        \Magenest\Affiliate\Model\AffiliateDiscountFactory $affiliateDiscountFactory,
        \Magento\Store\Model\Store $store,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewrite
    ) {
        $state->setAreaCode('frontend');
        $this->discountAffiliateFactory = $affiliateDiscountFactory;
        $this->_urlRewrite       = $urlRewrite;
        $this->store             = $store;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /**
 * @var \Magento\SalesRule\Model\Rule $shoppingCartPriceRule
*/
        // Create salu rule when upgrade data
//        $shoppingCartPriceRule = $objectManager->create('\Magento\SalesRule\Model\Rule');
//        $shoppingCartPriceRule
//            ->setName("Coupon For Affiliate User")
//            ->setIsActive(1)
//            ->setSimpleAction('by_percent')
//            ->setDiscountAmount(5)
//            ->setCouponType(2)
//            ->setUseAutoGeneration(1)
//            ->setCustomerGroupIds(array('0', '1', '2', '3'))
//            ->setWebsiteIds(['1']);
//        $shoppingCartPriceRule->save();
//
//        $resource = $objectManager->create('Magento\Config\Model\Config');
//        $resource->setDataByPath("magenest_affiliate/discount/rule_id", $shoppingCartPriceRule->getRuleId());
//        $resource->save();
//        $resource = $objectManager->create('Magento\Config\Model\Config');
//        $resource->setDataByPath("magenest_affiliate/discount/is_rule_created", 1);
//        $resource->save();
        // affiliate discount default
        $discount = $this->discountAffiliateFactory->create();
        $data = [
            'type'=>"config",
            'simple_action'=> \Magenest\Affiliate\Model\AffiliateDiscount::ACTION_PER_PRODUCT,
            'discount_amount' => "10",
        ];
        $discount->setData($data)->save();

        //UrlRewrite coupon apply default
        $urlRewriteModel = $this->_urlRewrite->create();
//        $urlRewriteModel->setUrlRewriteId(69);
        /* set current store id */
        $urlRewriteModel->setStoreId(1);
        /* this url is not created by system so set as 0 */
        $urlRewriteModel->setIsSystem(0);
        /* unique identifier - set random unique value to id path */
        $urlRewriteModel->setIdPath(69);
        /* set actual url path to target path field */
        $urlRewriteModel->setTargetPath("affiliate/coupon/apply");
        /* set requested path which you want to create */
        $urlRewriteModel->setRequestPath("coupon");
        /* set current store id */
        $urlRewriteModel->save();



        $installer->endSetup();
    }
}
