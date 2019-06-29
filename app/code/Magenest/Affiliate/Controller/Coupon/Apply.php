<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 8/10/17
 * Time: 10:07 AM
 */

namespace Magenest\Affiliate\Controller\Coupon;

use Magenest\Affiliate\Helper\Constant;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

class Apply extends Action
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


    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Controller\ResultFactory $result,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository
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
        if (!$this->_configHelper->isAffiliateEnable()) {
            return $this->_redirect(null);
        }
        $coupon = $this->getRequest()->getParam('coupon');
        $customerModel = $this->customerRepository->getByUniqueCode($coupon);
        $cpAllow = $customerModel?($customerModel->getStatus()==Constant::AFFILIATE_CUSTOMER_APPROVED):false;
        if ($customerModel && $cpAllow) {
            //set cookie
            $this->_eventManager->dispatch(
                'magenest_affiliate_set_cookie',
                [
                'unique_code' => $coupon
                ]
            );
            $returnUrl = $this->getRequest()->getParam('return_url');
            $urlRedirect = $returnUrl;
            $this->_redirect($urlRedirect);
        } else {
            $this->_redirect(null);
        }
    }
}
