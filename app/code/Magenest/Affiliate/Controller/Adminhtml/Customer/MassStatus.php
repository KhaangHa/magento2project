<?php

namespace Magenest\Affiliate\Controller\Adminhtml\Customer;

use Magenest\Affiliate\Model\Customer;
use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class MassStatus extends Action
{
    protected $filterBuilder;
    protected $_filter;
    protected $customerReporitory;
    protected $searchCriteriaBuilder;
    protected $customerInterfaceFactory;
    protected $customerFactory;
    protected $couponFactory;
    protected $config;

    public function __construct(
        Filter $filter,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        Action\Context $context,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magenest\Affiliate\Model\CustomerFactory $customerFactory,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory,
        \Magenest\Affiliate\Helper\ConfigHelper $config
    ) {
        $this->customerFactory = $customerFactory;
        $this->_filter = $filter;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        parent::__construct($context);
        $this->customerReporitory = $customerRepository;
        $this->customerInterfaceFactory = $customerInterfaceFactory;
        $this->config = $config;
    }

    public function execute()
    {
        $status = (int)$this->getRequest()->getParam('status');
        $collection = $this->_filter->getCollection($this->customerReporitory->getCollection());
        $total = 0;

        try {
            foreach ($collection as $item) {
                $this->customerReporitory->save($item->setData('status', $status));
                $total++;

//                Change status to log
                $id = $item->getData('customer_id');
                $customerConnection = $this->_objectManager->create('\Magento\Customer\Model\Customer')->load($id);
                $email = $customerConnection->getEmail();

                if($status == 2)
                    $currentStatus = "Approved";
                else if ($status ==3) $currentStatus = "Disable";

                $description = $email . " status changed to " . $currentStatus;

                $logConnection = $this->_objectManager->create('\Magenest\Affiliate\Model\Log');
                $logConnection->setType(1);
                $logConnection->setDescription($description);
                $logConnection->save();
            }
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', $total));
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
