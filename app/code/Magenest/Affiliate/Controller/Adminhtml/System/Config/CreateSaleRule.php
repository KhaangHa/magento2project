<?php
/**
 * Assembly_Payments extension
 *
 * @package   Assembly_Payments
 * @copyright Copyright (c) 2017 Assembly Payments (https://assemblypayments.com/)
 */

namespace Magenest\Affiliate\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class CreateSaleRule extends Action
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    protected $_config;

    protected $storeManager;

    protected $ruleFactory;

    protected $scopeConfig;


    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\SalesRule\Model\RuleFactory $ruleFactory,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->_config = $configHelper;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            /**
 * @var \Magento\Framework\Controller\Result\Json $resultJson
*/
            $result = $this->jsonFactory->create();
            $shoppingCartPriceRule = $this->ruleFactory->create();
            $shoppingCartPriceRule
                ->setName("Coupon For Affiliate User")
                ->setIsActive(1)
                ->setSimpleAction('by_percent')
                ->setDiscountAmount(5)
                ->setCouponType(2)
                ->setUseAutoGeneration(1)
                ->setCustomerGroupIds(array('0', '1', '2', '3'))
                ->setWebsiteIds(['1']);
            $shoppingCartPriceRule->save();

            $resource = $objectManager->create('Magento\Config\Model\Config');
            $resource->setDataByPath("magenest_affiliate/discount/rule_id", $shoppingCartPriceRule->getRuleId());
            $resource->save();
            $resource = $objectManager->create('Magento\Config\Model\Config');
            $resource->setDataByPath("magenest_affiliate/discount/is_rule_created", 1);
            $resource->save();

            return $result->setData(
                [
                'success' => true,
                ]
            );
        } catch (\Exception $exception) {
        }
    }
}
