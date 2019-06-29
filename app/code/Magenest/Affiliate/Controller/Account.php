<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 19:48
 */

namespace Magenest\Affiliate\Controller;

use Magenest\Affiliate\Helper\Constant;
use Magento\Framework\App\Action\Action;

abstract class Account extends Action
{
    protected $_customerSession;
    protected $_pageFactory;
    protected $jsonFactory;
    protected $_configHelper;
    protected $affiliateManagement;
    protected $customerInterfaceFactory;
    protected $customerRepository;
    protected $logger;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->jsonFactory = $resultJsonFactory;
        $this->_customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->customerInterfaceFactory = $customerInterfaceFactory;
        $this->_pageFactory = $pageFactory;
        $this->_configHelper = $configHelper;
        $this->affiliateManagement = $affiliateManagement;
        $this->logger = $logger;
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
}
