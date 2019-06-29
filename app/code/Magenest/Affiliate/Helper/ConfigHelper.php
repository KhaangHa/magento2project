<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 19:42
 */

namespace Magenest\Affiliate\Helper;

use Magenest\Affiliate\Model\PayPalMassPayService;
use Magento\Framework\App\Helper\Context;

class ConfigHelper extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $ruleFactory;
    protected $couponFactory;
    protected $_encryptor;
    protected $customeRepository;

    /**
     * @var Magenest\Affiliate\Model\ResourceModel\PpcAffiliate\Collection
     */
    protected $_ppcCollectionFactory;
    /**
     * @var Magenest\Affiliate\Model\ResourceModel\BannerAffiliate\Collection
     */
    protected $_bannerCollectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magenest\Affiliate\Model\CustomerFactory $customerRepository,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager
//        \Magenest\Affiliate\Model\ResourceModel\PpcAffiliate\CollectionFactory $ppcCollectionFactory,
//        \Magenest\Affiliate\Model\ResourceModel\BannerAffiliate\CollectionFactory $bannerCollectionFactory
    )
    {
        $this->_encryptor = $encryptor;
        $this->customeRepository = $customerRepository;
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
//        $this->_ppcCollectionFactory = $ppcCollectionFactory;
//        $this->_bannerCollectionFactory = $bannerCollectionFactory;
        parent::__construct($context);
    }

    public function isAutoEnroll()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/general/auto_enroll',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isNeedApprove()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/general/need_approved',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getAffiliateStatus()
    {
        $needApproved = $this->isNeedApprove();
        return $needApproved ? Constant::AFFILIATE_CUSTOMER_PENDING : Constant::AFFILIATE_CUSTOMER_APPROVED;
    }

    public function getUniqueCode()
    {
        $generateCoupon = $this->generateRandomString();
        $check = $this->customeRepository->create()->getCollection()->addFieldToFilter('unique_code', $generateCoupon);
        while ($check->getSize() > 0) {
            $generateCoupon = $this->generateRandomString();
            $check = $this->customeRepository->create()->getCollection()->addFieldToFilter('unique_code', $generateCoupon);
        }
        return $generateCoupon;
    }

    function generateRandomString($length = 20, $abc = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")
    {
        return substr(str_shuffle($abc), 0, $length);
    }

    public function getCommissionType()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/commission/commission_type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCommissionValue()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/commission/commission_value',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCommissionCondition()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/commission/commission_condition',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCommissionHold()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/commission/commission_hold',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isSubtractCommission()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/commission/subtract_commission',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getSubtractCommissionType()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/commission/subtract_commission_type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getSubtractCommissionValue()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/commission/subtract_commission_value',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isSaleRuleCreated()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/discount/is_rule_created',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getRuleId()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/discount/rule_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getBaseCurrencySymbol()
    {
        $baseCurrency = $this->scopeConfig->getValue(
            'currency/options/base',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $currency = $objectManager->create('Magento\Directory\Model\CurrencyFactory')->create()->load($baseCurrency);
        return $currency->getCurrencySymbol();
    }

    public function getCookieLifetime()
    {
        return abs(
            round(
                intval(
                    $this->scopeConfig->getValue(
                        'magenest_affiliate/general/cookie_lifetime',
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    )
                )
            )
        );
    }

    public function getCustomParam()
    {
        $param = trim(
            $this->scopeConfig->getValue(
                'magenest_affiliate/general/custom_param',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        );
        if (!$param) {
            return "coupon";
        }
        return $param;
    }

    public function getFrontendTransactionPageSize()
    {
        return 20;
    }

    public function getIsAutoWithdraw()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/withdraw/auto_withdraw',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getAllowedMethods()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/withdraw/allowed_methods',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getMinWithdraw()
    {
        return intval(
            $this->scopeConfig->getValue(
                'magenest_affiliate/withdraw/min',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        );
    }

    public function getMaxWithdraw()
    {
        return intval(
            $this->scopeConfig->getValue(
                'magenest_affiliate/withdraw/max',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        );
    }

    public function getEnableFeeWithdraw()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/withdraw/enable_fee',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getFeeType()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/withdraw/fee_type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getFeeValue()
    {
        return intval(
            $this->scopeConfig->getValue(
                'magenest_affiliate/withdraw/fee_value',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        );
    }

    public function getPayPalApiUsername()
    {
        $val = $this->scopeConfig->getValue('magenest_affiliate/payment/paypal/api_username');
        return $this->_encryptor->decrypt($val);
    }

    public function getPayPalApiPassword()
    {
        $val = $this->scopeConfig->getValue('magenest_affiliate/payment/paypal/api_password');
        return $this->_encryptor->decrypt($val);
    }

    public function getPayPalApiSignature()
    {
        $val = $this->scopeConfig->getValue('magenest_affiliate/payment/paypal/api_signature');
        return $this->_encryptor->decrypt($val);
    }

    public function getPayPalIsSandbox()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/payment/paypal/is_sandbox'
        );
    }

    public function getPayPalApiUrl()
    {
        $isSandbox = $this->getPayPalIsSandbox();
        if ($isSandbox) {
            return PayPalMassPayService::PAYPAL_API_SANDBOX_URL;
        } else {
            return PayPalMassPayService::PAYPAL_API_LIVE_URL;
        }
    }

    public function isCommissionEnable()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/commission/enabled'
        );
    }

    public function isDiscountEnable()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/discount/enabled'
        );
    }

    public function isAffiliateEnable()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/general/enable'
        );
    }

    public function getSimpleActionDiscount()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/discount/coupon_type'
        );
    }

    public function getAmountDiscount()
    {
        return $this->scopeConfig->getValue(
            'magenest_affiliate/discount/discount_value'
        );
    }


    public function getIp()
    {
        return $this->_remoteAddress->getRemoteAddress();
    }

    /**
     * [isLocalhost]
     * @return [boolean]
     */
    public function isLocalhost($remote_addr)
    {
        $whitelist = array('localhost', '127.0.0.1');
        if (in_array($remote_addr, $whitelist)) {
            return true;
        }
        return false;
    }

    protected function getIpAddress()
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $obj = $om->get('Magento\Framework\HTTP\PhpEnvironment\RemoteAddress');
        $ip = $obj->getRemoteAddress();
        return $ip;
    }

    public function updatePPCBalance($rawClick, $unique_code)
    {

    }

    public function countClickBanner($banner_id, $unique_code)
    {
        $bannerModel = $this->_objectManager->create('Magenest\Affiliate\Model\Banner')->load($banner_id);
        $banner = $bannerModel->getData();
//        $this->updatePPCBalance();
        if (!empty($banner) && $banner['status'] == 1) {
            $base_currency_code = $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
//            $current_ip = $this->getIp();
            $current_ip = '172.30.1.7';
            $isLocalhost = $this->isLocalhost($current_ip);
            if (!$isLocalhost) {
                $is_unique = 0;
                $click_unique = 0;

                $ppcModel = $this->_objectManager->create('Magenest\Affiliate\Model\Ppc')->getCollection();
                $ppcModel->addFieldToFilter('banner_id', $banner_id)
                    ->addFieldToFilter('unique_code', $unique_code)
                    ->addFieldToFilter('customer_ip', $current_ip);

                if (count($ppcModel) < 1) {
                    $is_unique = 1;
                    $click_unique = 1;
                }

                // CHECK ACTIVE PROGRAM
                $Ppc = $this->_objectManager->create('Magenest\Affiliate\Model\Ppc');
                $program = $this->_objectManager->create('Magenest\Affiliate\Model\Program')->load(\Magenest\Affiliate\Model\Program::TYPE_PAY_PER_CLICK, 'program_type_id');
                if ($program->getStatus() == \Magenest\Affiliate\Model\Program::STATUS_ACTIVE) {

                    // Add infomation when pay per click
                    $Ppc->setBannerId($banner_id)
                        ->setUniqueCode($unique_code)
                        ->setCustomerIp($current_ip)
                        ->setIsUnique($is_unique)
                        ->setBaseCurrencyCode($base_currency_code);
                    // Update Click times
                    $bannerModel->setClickRaw($banner['click_raw'] + 1)
                        ->setClickUnique($banner['click_unique'] + $click_unique);

                    //GET COMMISSION TIER
                    $configCommission = $this->_objectManager
                        ->create('Magenest\Affiliate\Model\ResourceModel\ProgramConfig\Collection')
                        ->addFieldToFilter('program_id', $program->getId());
                    $commissionTier = $configCommission->getData();

                    //GET NUMBER OF CLICK FROM CURRENT IP
                    $clickTier = $this->getClickTime($banner['id'], $unique_code, $current_ip);
                    $maxTier = $this->getMaxTier();
                    $isDuplicate = $this->checkDuplicateIp($current_ip);

                    foreach ($commissionTier as $item) {
                        //ONLY SET BALANCE WHEN CLICK = TIER
                        if ($item['tier'] == $clickTier[0]['tier'] + 1 && $isDuplicate == false) {
                            if ($is_unique)
                                $bannerModel->setClickUniqueCommission($bannerModel->getClickUniqueCommission() + $item['commission']);
                            else
                                $bannerModel->setClickRawCommission($bannerModel->getClickRawCommission() + $item['commission']);

                            $Ppc->setCommission($item['commission']);

                            $bannerModel->setExpense($banner['expense'] + $item['commission']);

                            $customerModel = $this->_objectManager
                                ->create('Magenest\Affiliate\Model\Customer')
                                ->load($unique_code, 'unique_code');
                            $customer = $customerModel->getData();

                            if (!empty($customer) && isset($customer['balance'])){
                                $customerModel->setBalance($customer['balance'] + $item['commission']);
                                $customerModel->setTotalCommission($customer['total_commission'] + $item['commission']);
                                $customerModel->save();
                            }
                        }
                        else if ($item['tier'] == $maxTier[0]['tier'] && $clickTier[0]['tier'] > $maxTier[0]['tier'] && $isDuplicate == false)
                        {
                            $bannerModel->setClickUniqueCommission($bannerModel->getClickUniqueCommission() + $item['commission']);

                            $Ppc->setCommission($item['commission']);

                            $bannerModel->setExpense($banner['expense'] + $item['commission']);

                            $customerModel = $this->_objectManager
                                ->create('Magenest\Affiliate\Model\Customer')
                                ->load($unique_code, 'unique_code');
                            $customer = $customerModel->getData();

                            if (!empty($customer) && isset($customer['balance'])){
                                $customerModel->setBalance($customer['balance'] + $item['commission']);
                                $customerModel->setTotalCommission($customer['total_commission'] + $item['commission']);
                                $customerModel->save();
                            }
                        }
                    }
                    $bannerModel->save();
                    $Ppc->save();
                }
            }
        }
    }

    public function getClickTime($bannerId, $unique_code, $ip)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $query = "SELECT COUNT(*) as tier FROM magenest_affiliate_ppc
                WHERE banner_id = ". $bannerId ." and is_unique = 1";
        $result = $connection->fetchAll($query);

        return $result;
    }

    public function checkDuplicateIp($ip)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $query = "SELECT customer_ip FROM magenest_affiliate_ppc
                WHERE customer_ip = '" . $ip . "'";
        $result = $connection->fetchAll($query);
        if(empty($result))
            return false;
        return true;
    }

    public function getMaxTier(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
        $connection = $resource->getConnection();

        $commission = $connection->select()
            ->from($resource->getTableName('magenest_affiliate_program_config_commission'), ['max(tier) as tier']
            )->where('program_type_id = 1');
        return $connection->fetchAll($commission);
    }
}