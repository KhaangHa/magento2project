<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 19:58
 */

namespace Magenest\Affiliate\Block;

use Magenest\Affiliate\Helper\Constant;
use Magento\Framework\Data\Collection;
use mysql_xdevapi\Exception;

class Account extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry;
    protected $_customerSession;
    protected $_configHelper;
    protected $withdrawRepository;
    protected $customerInterfaceFactory;
    protected $customerRepository;
    protected $downlineRepository;
    protected $transactionRepository;

    protected $searchCriteriaBuilder;
    protected $sortOrderBuilder;
    protected $cookie;

    public $from;
    public $to;
    public $direction;
    public $fieldToSort;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Api\WithdrawRepositoryInterface $withdrawRepository,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory,
        \Magenest\Affiliate\Api\DownlineRepositoryInterface $downlineRepository,
        \Magenest\Affiliate\Api\TransactionRepositoryInterface $transactionRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
        \Magenest\Affiliate\Cookie\Cookie $cookie
    ) {
    
        parent::__construct($context);
        $this->_coreRegistry = $registry;
        $this->_customerSession = $customerSession;
        $this->_configHelper = $configHelper;
        $this->downlineRepository = $downlineRepository;
        $this->customerRepository = $customerRepository;
        $this->customerInterfaceFactory = $customerInterfaceFactory;
        $this->withdrawRepository = $withdrawRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->transactionRepository = $transactionRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->cookie = $cookie;
    }

    public function getPageData()
    {
        $customerId = $this->_customerSession->getCustomerId();
    }


    public function getIsAffiliate()
    {
        try {
            $customerId = $this->_customerSession->getCustomerId();
            $customer = $this->customerRepository->getByCustomerId($customerId);
            if (!$customer/** || $customer->getStatus() != Constant::AFFILIATE_CUSTOMER_APPROVED*/) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param int $customerId
     * @return int
     */
    public function getUplineOfCustomer($customerId)
    {
        $downlineCustomer = $this->downlineRepository->getByCustomerDownline($customerId);
        if ($downlineCustomer) {
            return $downlineCustomer->getData("customer_id_upline");
        } else {
            return null;
        }
    }

    public function getCustomerId(){
        return $customerId = $this->_customerSession->getCustomerId();
    }

    public function getDataLink()
    {
        try {
            $customerId = $this->_customerSession->getCustomerId();
            $customer = $this->customerRepository->getByCustomerId($customerId);
            $dataReturn = [];
            $dataReturn['is_affiliate'] = $this->getIsAffiliate();
            $dataReturn['current_menu'] = 2;
            if ($customer) {
                $dataReturn['status'] = $customer->getStatus();
                $dataReturn['balance'] = $customer->getBalance();
                $dataReturn['commission'] = $customer->getTotalCommission();
                $dataReturn['withdraw'] = $customer->getTotalPaid();
                $dataReturn['paypal_email'] = $customer->getPaypalEmail();
//                $dataReturn['upline_customer'] = $this->getUplineOfCustomer($customerId);
                $dataReturn['upline_customer'] = $customerId; //auto set upline as current cus
                $dataReturn['unique_code'] = $customer->getUniqueCode();
            }
            return $dataReturn;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getConfig()
    {
        return $this->_configHelper;
    }

    public function getCustomParam()
    {
        return $this->_configHelper->getCustomParam();
    }

    public function getDataTransactionPage()
    {
        $customerId = $this->_customerSession->getCustomerId();
        $customer = $this->customerRepository->getByCustomerId($customerId);
        $dataReturn = [];
        $dataReturn['current_menu'] = 3;
        $dataReturn['is_affiliate'] = $this->getIsAffiliate();
        $dataReturn['balance'] = $customer->getBalance();
        $dataReturn['currency_symbol'] = $this->_configHelper->getBaseCurrencySymbol();
        return $dataReturn;
    }

    public function getDataSettingPage()
    {
        $customerId = $this->_customerSession->getCustomerId();
        $customer = $this->customerRepository->getByCustomerId($customerId);
        $dataReturn = [];
        $dataReturn['current_menu'] = 5;
        if ($customer) {
            $dataReturn['paypal_email'] = $customer->getPaypalEmail();
        }
        return $dataReturn;
    }

    public function getDataWithdrawPage()
    {
        $customerId = $this->_customerSession->getCustomerId();
        $customer = $this->customerRepository->getByCustomerId($customerId);
        $dataReturn = [];
        $dataReturn['current_menu'] = 4;
        $dataReturn['is_affiliate'] = $this->getIsAffiliate();
        $dataReturn['balance'] = $customer->getBalance();
        $dataReturn['currency_symbol'] = $this->_configHelper->getBaseCurrencySymbol();
        return $dataReturn;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getCollection()) {
            // create pager block for collection
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'affiliate.record.pager'
            )->setAvailableLimit(array(5 => 5, 10 => 10, 15 => 15, 20 => 20, 50 => 50, 100 => 100))->setCollection(
                $this->getCollection()
            );
            $this->setChild('pager', $pager);// set pager block in layout
        }
        return $this;
    }

    /**
     * @return string
     */
    // method for get pager html
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getCollection()
    {
        $layout = $this->getNameInLayout();
        switch ($layout) {
            case 'affiliate_transaction':
                return $this->getTransactions(false);
            case 'affiliate_withdraw':
                return $this->getWithdraws(false);
            default:
                return false;
        }
    }

    public function getTransactions($isTemplate = true)
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $this->fieldToSort = ($this->getRequest()->getParam('field')) ? $this->getRequest()->getParam('field') : 'id';
        $this->direction = ($this->getRequest()->getParam('direction')) ? strtoupper($this->getRequest()->getParam('direction')) : Collection::SORT_ORDER_DESC;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $from = $this->getRequest()->getParam('from');
        $this->from = $from;
        $to = $this->getRequest()->getParam('to');
        $this->to = $to;
        $customerId = $this->_customerSession->getCustomerId();
        $customer = $this->customerRepository->getByCustomerId($customerId);
        $dataReturn = [];
        if ($customer) {
            $createdAtSort = $this->sortOrderBuilder
                ->setField($this->fieldToSort)
                ->setDirection($this->direction)
                ->create();
            $this->searchCriteriaBuilder->addFilter('customer_id_upline', $customerId);
            $this->searchCriteriaBuilder->setCurrentPage($page);
            $this->searchCriteriaBuilder->setPageSize($pageSize);
            $this->searchCriteriaBuilder->addSortOrder($createdAtSort);
            if ($from) {
                $this->searchCriteriaBuilder->addFilter('created_at', $from, 'gteq');
            }

            if ($to) {
                $to = $to . " 23:59:59";
                $this->searchCriteriaBuilder->addFilter('created_at', $to, 'lteq');
            }

            if ($isTemplate === false) {
                $collection = $this->transactionRepository->getCollection();
                $collection->addFieldToFilter('customer_id_upline', $customerId);
                if ($from) {
                    $collection->addFilter('created_at', $from, 'gteq');
                }

                if ($to) {
                    $to = $to . " 23:59:59";
                    $collection->addFilter('created_at', $to, 'lteq');
                }
                $collection->setCurPage($page);
                $collection->setPageSize($pageSize);
                $collection->setOrder($createdAtSort->getField(), $createdAtSort->getDirection());
                return $collection;
            }
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $searchResults = $this->transactionRepository->getList($searchCriteria);
            $transactions = $searchResults->getItems();
            $transactionData = [];
            foreach ($transactions as $transaction) {
                $transactionData[] = [
                    'id' => $transaction->getId(),
                    'date' => $transaction->getCreatedTime(),
                    'commission' => $transaction->getCommissionMoney(),
                    'subtract_commission' => $transaction->getSubtractMoney(),
                    'description' => $transaction->getDescription(),
                    'customer_id_downline' => $this->getNameFromId($transaction->getDownline())
                ];
            }
            $dataReturn = $transactionData;
        }
        return $dataReturn;
    }

    public function getWithdraws($isTemplate = true)
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $this->fieldToSort = ($this->getRequest()->getParam('field')) ? $this->getRequest()->getParam('field') : 'id';
        $this->direction = ($this->getRequest()->getParam('direction')) ? strtoupper($this->getRequest()->getParam('direction')) : Collection::SORT_ORDER_DESC;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $from = $this->getRequest()->getParam('from');
        $this->from = $from;
        $to = $this->getRequest()->getParam('to');
        $this->to = $to;
        $customerId = $this->_customerSession->getCustomerId();
        $customer = $this->customerRepository->getByCustomerId($customerId);
        $dataReturn = [];
        if ($customer) {
            $createdAtSort = $this->sortOrderBuilder
                ->setField($this->fieldToSort)
                ->setDirection($this->direction)
                ->create();
            $this->searchCriteriaBuilder->addFilter('customer_id', $customerId);
            $this->searchCriteriaBuilder->setCurrentPage($page);
            $this->searchCriteriaBuilder->setPageSize($pageSize);
            $this->searchCriteriaBuilder->addSortOrder($createdAtSort);
            if ($from) {
                $this->searchCriteriaBuilder->addFilter('created_at', $from, 'gteq');
            }

            if ($to) {
                $to = $to . " 23:59:59";
                $this->searchCriteriaBuilder->addFilter('created_at', $to, 'lteq');
            }

            if ($isTemplate === false) {
                $collection = $this->withdrawRepository->getCollection();
                $collection->addFieldToFilter('customer_id', $customerId);
                if ($from) {
                    $collection->addFilter('created_at', $from, 'gteq');
                }

                if ($to) {
                    $to = $to . " 23:59:59";
                    $collection->addFilter('created_at', $to, 'lteq');
                }
                $collection->setCurPage($page);
                $collection->setPageSize($pageSize);
                $collection->setOrder($createdAtSort->getField(), $createdAtSort->getDirection());
                return $collection;
            }

            $searchCriteria = $this->searchCriteriaBuilder->create();
            $searchResults = $this->withdrawRepository->getList($searchCriteria);
            $withdraws = $searchResults->getItems();
            $withdrawData = [];
            foreach ($withdraws as $withdraw) {
                $withdrawData[] = [
                    'id' => $withdraw->getId(),
                    'date' => $withdraw->getCreatedTime(),
                    'money' => $withdraw->getMoney(),
                    'method' => $withdraw->getMethod(),
                    'status' => $withdraw->getStatus()
                ];
            }
            $dataReturn = $withdrawData;
        }
        return $dataReturn;
    }

    public function getNameFromId($customerId)
    {
        if ($customerId) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            /**
             * @var \Magento\Customer\Api\Data\CustomerInterface $customer
             */
            $customer = $objectManager->create('Magento\Customer\Api\CustomerRepositoryInterface')->getById($customerId);
            return $customer->getFirstname() . ' ' . $customer->getLastname();
        }
        return "Guest";
    }

    public function getCookie(){
        $cokie =  $this->cookie->get();
        if($cokie){
            return $cokie;
        }
       return null;
    }

    public function getAllBanner(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $query = "SELECT * FROM magenest_affiliate_banner";
        $result = $connection->fetchAll($query);
        return $result;
    }

    public function getBanner($id){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $objectManager->create('Magenest\Affiliate\Model\ResourceModel\Banner\Collection');
        $model->addFieldToFilter('campaign_id', $id);
        return $model->getData();

    }
    public function getCampaign(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $objectManager->create('Magenest\Affiliate\Model\ResourceModel\Campaign\Collection');
        $now = date("Y/m/d");
        $model->addFieldToFilter('status', 1)
                ->addFieldToFilter('end_time', array('gt' => $now));
        return $model->getData();
    }

    public function getAllCustomer(){
        try{
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $affiliateCustomer = $objectManager->create('Magenest\Affiliate\Model\ResourceModel\Customer\Collection')->getData();
//            $customer = $objectManager->create('Magento\Customer\Model\Customer')->addFieldToFilter('id',$affiliateCustomer['customer_id']);
            return $affiliateCustomer;
        }catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __($e->getMessage()));
        }

    }
}
