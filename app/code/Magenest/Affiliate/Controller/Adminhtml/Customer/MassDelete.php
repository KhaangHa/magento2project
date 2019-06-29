<?php

namespace Magenest\Affiliate\Controller\Adminhtml\Customer;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class MassDelete extends Action
{
    protected $filterBuilder;
    protected $_filter;
    protected $customerReporitory;
    protected $searchCriteriaBuilder;
    protected $customerInterfaceFactory;
    protected $customerFactory;
    protected $couponFactory;

    public function __construct(
        Filter $filter,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        Action\Context $context,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magenest\Affiliate\Model\CustomerFactory $customerFactory,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magenest\Affiliate\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory,
        \Magento\SalesRule\Model\CouponFactory $couponFactory
    ) {
        $this->customerFactory = $customerFactory;
        $this->_filter = $filter;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        parent::__construct($context);
        $this->customerReporitory = $customerRepository;
        $this->customerInterfaceFactory = $customerInterfaceFactory;
        $this->couponFactory = $couponFactory;
    }

    public function execute()
    {
        $collection = $this->_filter->getCollection($this->customerReporitory->getCollection());
        $total = 0;

        try {
            foreach ($collection as $item) {
                $this->customerReporitory->delete($item);
                $code = $item->getUniqueCode();
                $coupon = $this->couponFactory->create()->loadByCode($code);
                $coupon->delete();
                $total++;
            }
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $total));
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
