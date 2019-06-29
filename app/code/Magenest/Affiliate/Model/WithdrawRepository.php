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
use Magenest\Affiliate\Api\Data\WithdrawInterface;
use Magenest\Affiliate\Api\Data\WithdrawSearchResultInterface;
use Magenest\Affiliate\Api\Data\WithdrawSearchResultInterfaceFactory;
use Magenest\Affiliate\Api\WithdrawRepositoryInterface;
use Magenest\Affiliate\Model\ResourceModel\Withdraw\CollectionFactory as WithdrawCollectionFactory;
use Magenest\Affiliate\Model\ResourceModel\Withdraw\Collection;

class WithdrawRepository implements WithdrawRepositoryInterface
{
    /**
     * @var Withdraw
     */
    private $withdrawFactory;

    /**
     * @var WithdrawCollectionFactory
     */
    private $withdrawCollectionFactory;

    private $searchResultFactory;

    public function __construct(
        WithdrawFactory $withdrawFactory,
        WithdrawCollectionFactory $withdrawCollectionFactory,
        WithdrawSearchResultInterfaceFactory $withdrawSearchResultInterfaceFactory
    ) {
        $this->withdrawFactory = $withdrawFactory;
        $this->withdrawCollectionFactory = $withdrawCollectionFactory;
        $this->searchResultFactory = $withdrawSearchResultInterfaceFactory;
    }

    // ... getById, save and delete methods listed above ...

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->withdrawCollectionFactory->create();

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
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
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
     * @return \Magenest\Affiliate\Api\Data\WithdrawInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $withdraw = $this->withdrawFactory->create();
        $withdraw->getResource()->load($withdraw, $id);
        if (! $withdraw->getId()) {
            return false;
        }
        return $withdraw;
    }

    /**
     * Delete test by ID.
     */
    public function deleteById($id)
    {
        $withdraw = $this->getById($id);
        $this->delete($withdraw);
    }

    public function save(WithdrawInterface $withdraw)
    {
        $withdraw->getResource()->save($withdraw);
        return $withdraw;
    }

    public function delete(WithdrawInterface $withdraw)
    {
        $withdraw->getResource()->delete($withdraw);
    }

    /**
     * @return \Magenest\Affiliate\Model\ResourceModel\Withdraw\Collection
     */
    public function getCollection()
    {
        return $this->withdrawCollectionFactory->create();
    }
}
