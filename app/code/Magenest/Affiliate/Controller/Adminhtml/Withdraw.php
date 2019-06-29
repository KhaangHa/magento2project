<?php
/**
 * Created by Magenest.
 * Author: Pham Quang Hau
 * Date: 12/08/2016
 * Time: 16:41
 */

namespace Magenest\Affiliate\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

abstract class Withdraw extends Action
{
    protected $_pageFactory;

    protected $_coreRegistry;

    protected $customerRepository;

    protected $withdrawRepository;

    protected $paypalMassPaymentService;

    protected $_storeManager;

    public function __construct(
        Action\Context $context,
        PageFactory $pageFactory,
        Registry $registry,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Api\WithdrawRepositoryInterface $withdrawRepository,
        \Magenest\Affiliate\Model\PayPalMassPayService $payPalMassPayService,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
        $this->customerRepository = $customerRepository;
        $this->withdrawRepository = $withdrawRepository;
        $this->paypalMassPaymentService = $payPalMassPayService;
        $this->_storeManager = $storeManager;
    }

    protected function _initAction()
    {
        /**
 * @var \Magento\Backend\Model\View\Result\Page $resultPage
*/
        $resultPage = $this->_pageFactory->create();
        $resultPage->setActiveMenu('Magenest_Affiliate::withdraw')
            ->addBreadcrumb(__('Affiliate Withdraw'), __('Affiliate Withdraw'));

        $resultPage->getConfig()->getTitle()->set(__('Affiliate Withdraw'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Affiliate::withdraw');
    }
}
