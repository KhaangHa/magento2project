<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 14/08/2017
 * Time: 09:49
 */

namespace Magenest\Affiliate\Controller\Test;

use Magenest\Affiliate\Helper\Constant;
use Magento\Framework\App\Action\Context;
use Magenest\Affiliate\Api\CustomerRepositoryInterface;

class Test extends \Magento\Framework\App\Action\Action
{
    private $eventManager;
    protected $cookieManager;
    protected $cookieMetadataFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magenest\Affiliate\Api\OrderRepositoryInterface $orderRepository,
        \Magenest\Affiliate\Api\Data\OrderInterfaceFactory $orderInterfaceFactory,
        \Magenest\Affiliate\Api\TransactionRepositoryInterface $transactionRepository,
        \Magento\Framework\Event\Manager $eventManager,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Cookie\Cookie $cookie,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
    ) {
        parent::__construct($context);
        $this->orderRepository = $orderRepository;
        $this->orderInterfaceFactory = $orderInterfaceFactory;
        $this->transactionRepository = $transactionRepository;
        $this->eventManager = $eventManager;
        $this->customerRepository = $customerRepository;
        $this->cookie = $cookie;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    public function execute()
    {
    }
}
