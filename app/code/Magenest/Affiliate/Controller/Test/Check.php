<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 07/03/2018
 * Time: 09:01
 */

namespace Magenest\Affiliate\Controller\Test;

use Magenest\Affiliate\Helper\Constant;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

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

    protected $couponFactory;

    protected $customerRepository;

    protected $cookie;

    protected $saleRule;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Controller\ResultFactory $result,
        \Magento\SalesRule\Model\CouponFactory $couponFactory,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Cookie\Cookie $cookie,
        \Magento\SalesRule\Model\RuleFactory $saleRule

    ) {
        parent::__construct($context);
        $this->jsonFactory = $resultJsonFactory;
        $this->_customerSession = $customerSession;
        $this->_pageFactory = $pageFactory;
        $this->_configHelper = $configHelper;
        $this->affiliateManagement = $affiliateManagement;
        $this->_storeManager = $storeManager;
        $this->resultRedirect = $result;
        $this->couponFactory = $couponFactory;
        $this->customerRepository = $customerRepository;
        $this->cookie = $cookie;
        $this->saleRule = $saleRule;
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
//      $cookie = $this->cookie->get();
//      if(isset($cookie)){
//          $codeDiscount = $cookie['unique_code'];
            $codeDiscount = "rcLJNO4RnIp9gBmK";
          $salesRule = $this->saleRule->create()->getDataByKey($codeDiscount);
          $discount = 20;
          return $discount;
//      }
    }
}
