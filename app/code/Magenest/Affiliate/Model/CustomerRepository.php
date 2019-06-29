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
use Magenest\Affiliate\Api\Data\CustomerInterface;
use Magenest\Affiliate\Api\Data\CustomerSearchResultInterface;
use Magenest\Affiliate\Api\Data\CustomerSearchResultInterfaceFactory;
use Magenest\Affiliate\Api\CustomerRepositoryInterface;
use Magenest\Affiliate\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magenest\Affiliate\Model\ResourceModel\Customer\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * @var Customer
     */
    private $customerFactory;

    /**
     * @var CustomerCollectionFactory
     */
    private $customerCollectionFactory;

    private $searchResultFactory;

    public function __construct(
        CustomerFactory $customerFactory,
        CustomerCollectionFactory $customerCollectionFactory,
        CustomerSearchResultInterfaceFactory $customerSearchResultInterfaceFactory
    ) {
        $this->customerFactory = $customerFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->searchResultFactory = $customerSearchResultInterfaceFactory;
    }

    // ... getById, save and delete methods listed above ...

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->customerCollectionFactory->create();

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
     * @return \Magenest\Affiliate\Api\Data\CustomerInterface|bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $customer = $this->customerFactory->create();
        $customer->getResource()->load($customer, $id);
        if (! $customer->getId()) {
            return false;
        }
        return $customer;
    }

    /**
     * Delete test by ID.
     */
    public function deleteById($id)
    {
        $customer = $this->getById($id);
        $this->delete($customer);
    }

    public function save(CustomerInterface $customer)
    {
        $customer->getResource()->save($customer);
        return $customer;
    }

    public function delete(CustomerInterface $customer)
    {
        $customer->getResource()->delete($customer);
    }


    public function getCollection()
    {
        return $this->customerFactory->create()->getCollection();
    }

    public function getByCustomerId($customerId)
    {
        $customer = $this->customerFactory->create();
        $customer->getResource()->load($customer, $customerId, "customer_id");
        if (! $customer->getId()) {
            return false;
        }
        return $customer;
    }


    public function getByUniqueCode($code)
    {
        $customer = $this->customerFactory->create();
        $customer->getResource()->load($customer, $code, "unique_code");
        if (!$customer->getId()) {
            return false;
        }

        return $customer;
    }

    /**
     * @return \Magenest\Affiliate\Api\Data\CustomerInterface[]
     */
    public function getListItem()
    {
        return $this->customerFactory->create()->getCollection()->getItems();
    }
}
