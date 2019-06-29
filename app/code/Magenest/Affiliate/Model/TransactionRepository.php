<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 20:56
 */

namespace Magenest\Affiliate\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magenest\Affiliate\Api\Data\TransactionInterface;
use Magenest\Affiliate\Api\Data\TransactionSearchResultInterface;
use Magenest\Affiliate\Api\Data\TransactionSearchResultInterfaceFactory;
use Magenest\Affiliate\Api\TransactionRepositoryInterface;
use Magenest\Affiliate\Model\ResourceModel\Transaction\CollectionFactory as TransactionCollectionFactory;
use Magenest\Affiliate\Model\ResourceModel\Transaction\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @var Transaction
     */
    private $transactionFactory;

    /**
     * @var TransactionCollectionFactory
     */
    private $transactionCollectionFactory;

    private $searchResultFactory;

    protected $searchCriteriaBuilder;

    public function __construct(
        TransactionFactory $transactionFactory,
        TransactionCollectionFactory $transactionCollectionFactory,
        TransactionSearchResultInterfaceFactory $transactionSearchResultInterfaceFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->transactionFactory = $transactionFactory;
        $this->transactionCollectionFactory = $transactionCollectionFactory;
        $this->searchResultFactory = $transactionSearchResultInterfaceFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    // ... getById, save and delete methods listed above ...

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->transactionCollectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);
        $collection->load();
        return $this->buildSearchResult($searchCriteria, $collection);
    }

    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @param int $id
     * @return \Magenest\Affiliate\Api\Data\TransactionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $transaction = $this->transactionFactory->create();
        $transaction->getResource()->load($transaction, $id);
        if (!$transaction->getId()) {
            return null;
        }
        return $transaction;
    }

    /**
     * Delete test by ID.
     */
    public function deleteById($id)
    {
        $transaction = $this->getById($id);
        $this->delete($transaction);
    }

    public function save(TransactionInterface $transaction)
    {
        $transaction->getResource()->save($transaction);
        return $transaction;
    }

    public function delete(TransactionInterface $transaction)
    {
        $transaction->getResource()->delete($transaction);
    }

    /**
     * @param int $invoiceId
     * @return \Magenest\Affiliate\Api\Data\TransactionInterface
     */
    public function getByInvoiceId($invoiceId)
    {
        $transaction = $this->transactionCollectionFactory->create();
        $transaction->addFieldToFilter('invoice_id', $invoiceId);
        if (!$transaction->getData()) {
            return null;
        }
        return $transaction->getFirstItem();
    }

    /**
     * @param $orderId
     * @return float
     */
    public function getTotalCommissionByOrderId($orderId)
    {
        $commissionTotal = 0;
        if ($orderId == null) {
            return $commissionTotal;
        }
        $transactionCollection = $this->transactionCollectionFactory->create();
        $transactionCollection->addFieldToFilter('order_id', $orderId);
        if (!$transactionCollection->getData()) {
            return $commissionTotal;
        }
        foreach ($transactionCollection as $transaction) {
            $commissionTotal += $transaction->getData('receive_money');
        }
        return $commissionTotal;
    }

    /**
     * @param int $customerId
     * @return \Magenest\Affiliate\Api\Data\TransactionInterface[]
     */
    public function getListByUplineCustomerId($customerId)
    {
        $this->searchCriteriaBuilder->addFilter('customer_id_upline', $customerId);
        $this->searchCriteriaBuilder->addFilter('count_down', "-1", "gt");
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->getList($searchCriteria);
        return $searchResults->getItems();
    }

    /**
     * @return \Magenest\Affiliate\Model\ResourceModel\Transaction\Collection
     */
    public function getCollection()
    {
        return $this->transactionCollectionFactory->create();
    }
}
