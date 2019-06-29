<?php
/**
 * Created by Magenest
 * User: Luu Thanh Thuy
 * Date: 09/08/2016
 * Time: 15:43
 */

namespace Magenest\Affiliate\Observer\Customer\Register;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Success implements ObserverInterface
{
    protected $_messageManager;

    protected $_configHelper;

    protected $affiliateManagement;

    protected $_customerSession;

    protected $customerRepository;

    protected $cookie;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magento\Customer\Model\Session $customerSession,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Cookie\Cookie $cookie
    ) {
        $this->_customerSession = $customerSession;
        $this->_messageManager = $messageManager;
        $this->_configHelper = $configHelper;
        $this->affiliateManagement = $affiliateManagement;
        $this->customerRepository = $customerRepository;
        $this->cookie = $cookie;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $autoEnrollEnable = $this->_configHelper->isAutoEnroll();
            if ($autoEnrollEnable) {
                $customer = $observer->getEvent()->getCustomer();
                $customerId = $customer->getId();
                $customerIdUpline = null;
                $cpCookie = $this->cookie->get();
                $customerUpline = $this->customerRepository->getByUniqueCode($cpCookie);
                if ($customerUpline) {
                    $customerIdUpline = $customerUpline->getCustomerId();
                }
                //$cookie = \Magento\Framework\App\ObjectManager::getInstance()->get('Magenest\Affiliate\Cookie\Cookie');
                //$customerIdUpline = $cookie->get();
                $customerAffiliateStatus = $this->_configHelper->getAffiliateStatus();
                $this->affiliateManagement->joinAffiliate($customerId, $customerIdUpline, $customerAffiliateStatus);
                $this->cookie->delete();
//                ADD LOG DATA
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
                $log = $objectManager->create('Magento\Directory\Model\Log')->create();

            }
        } catch (\Exception $exception) {
        }
    }
}
