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
use Magenest\Affiliate\Api\Data\DownlineInterface;
use Magenest\Affiliate\Api\Data\DownlineSearchResultInterface;
use Magenest\Affiliate\Api\Data\DownlineSearchResultInterfaceFactory;
use Magenest\Affiliate\Api\DownlineRepositoryInterface;
use Magenest\Affiliate\Model\ResourceModel\Downline\CollectionFactory as DownlineCollectionFactory;
use Magenest\Affiliate\Model\ResourceModel\Downline\Collection;

class DownlineRepository implements DownlineRepositoryInterface
{
    /**
     * @var Downline
     */
    private $downlineFactory;

    /**
     * @var DownlineCollectionFactory
     */
    private $downlineCollectionFactory;

    private $searchResultFactory;

    public function __construct(
        DownlineFactory $downlineFactory,
        DownlineCollectionFactory $downlineCollectionFactory,
        DownlineSearchResultInterfaceFactory $downlineSearchResultInterfaceFactory
    ) {
        $this->downlineFactory = $downlineFactory;
        $this->downlineCollectionFactory = $downlineCollectionFactory;
        $this->searchResultFactory = $downlineSearchResultInterfaceFactory;
    }

    // ... getById, save and delete methods listed above ...

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->downlineCollectionFactory->create();

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
     * @return \Magenest\Affiliate\Api\Data\DownlineInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $downline = $this->downlineFactory->create();
        $downline->getResource()->load($downline, $id);
        if (! $downline->getId()) {
            return null;
        }
        return $downline;
    }

    /**
     * Delete test by ID.
     */
    public function deleteById($id)
    {
        $downline = $this->getById($id);
        $this->delete($downline);
    }

    public function save(DownlineInterface $downline)
    {
        $downline->getResource()->save($downline);
        return $downline;
    }

    public function delete(DownlineInterface $downline)
    {
        $downline->getResource()->delete($downline);
    }

    /**
     * @param int $customerId
     * @return \Magenest\Affiliate\Api\Data\DownlineInterface|bool
     */
    public function getByCustomerDownline($customerId)
    {
        $downline = $this->downlineFactory->create();
        $downline->getResource()->load($downline, $customerId, "customer_id_downline");
        if (! $downline->getId()) {
            return false;
        }
        return $downline;
    }

    public function findUplineCustomerId($customerId)
    {
        $downlineCustomer = $this->getByCustomerDownline($customerId);
        if ($downlineCustomer) {
            return $downlineCustomer->getData('customer_id_upline');
        }
        return null;
    }
}
