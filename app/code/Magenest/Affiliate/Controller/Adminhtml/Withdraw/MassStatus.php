<?php

namespace Magenest\Affiliate\Controller\Adminhtml\Withdraw;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class MassStatus extends Action
{
    protected $filterBuilder;
    protected $_filter;
    protected $withdrawReporitory;
    protected $searchCriteriaBuilder;
    protected $withdrawInterfaceFactory;
    protected $withdrawFactory;

    public function __construct(
        Filter $filter,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        Action\Context $context,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magenest\Affiliate\Model\WithdrawFactory $withdrawFactory,
        \Magenest\Affiliate\Api\WithdrawRepositoryInterface $withdrawRepository,
        \Magenest\Affiliate\Api\Data\WithdrawInterfaceFactory $withdrawInterfaceFactory
    ) {
        $this->withdrawFactory = $withdrawFactory;
        $this->_filter = $filter;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        parent::__construct($context);
        $this->withdrawReporitory = $withdrawRepository;
        $this->withdrawInterfaceFactory = $withdrawInterfaceFactory;
    }

    public function execute()
    {
        $status = (int)$this->getRequest()->getParam('status');
        $collection = $this->_filter->getCollection($this->withdrawReporitory->getCollection());
        $total = 0;

        try {
            foreach ($collection as $item) {
                $this->withdrawReporitory->save($item->setData('status', $status));
                $total++;
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
