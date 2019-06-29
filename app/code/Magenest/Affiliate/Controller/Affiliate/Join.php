<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 11/08/2017
 * Time: 18:17
 */

namespace Magenest\Affiliate\Controller\Affiliate;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

class Join extends Action
{
    protected $_customerSession;
    protected $_configHelper;
    protected $affiliateManagement;
    protected $customerRepository;
    protected $cookie;

    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Model\AffiliateManagement $affiliateManagement,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Cookie\Cookie $cookie
    ) {
        parent::__construct($context);
        $this->_customerSession = $customerSession;
        $this->_configHelper = $configHelper;
        $this->affiliateManagement = $affiliateManagement;
        $this->customerRepository = $customerRepository;
        $this->cookie = $cookie;
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
        try {
            $customerId = $this->_customerSession->getCustomer()->getId();
            $customerIdUpline = null;
            $cpCookie = $this->cookie->get();
            $customerUpline = $this->customerRepository->getByUniqueCode($cpCookie);
            if ($customerUpline) {
                $customerIdUpline = $customerUpline->getCustomerId();
            }
            $customerAffiliateStatus = $this->_configHelper->getAffiliateStatus();
            $this->affiliateManagement->joinAffiliate($customerId, $customerIdUpline, $customerAffiliateStatus);
            $this->cookie->delete();
            $this->messageManager->addSuccessMessage("Join Affiliate Success");
            $this->_redirect("*/account/dashboard");
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage("Join Affiliate Error");
        }
    }
}
