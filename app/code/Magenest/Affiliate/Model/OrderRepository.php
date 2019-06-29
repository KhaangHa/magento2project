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
use Magenest\Affiliate\Api\Data\OrderInterface;
use Magenest\Affiliate\Api\Data\OrderSearchResultInterface;
use Magenest\Affiliate\Api\Data\OrderSearchResultInterfaceFactory;
use Magenest\Affiliate\Api\OrderRepositoryInterface;
use Magenest\Affiliate\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magenest\Affiliate\Model\ResourceModel\Order\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * @var Order
     */
    private $orderFactory;

    /**
     * @var OrderCollectionFactory
     */
    private $orderCollectionFactory;

    private $searchResultFactory;

    public function __construct(
        OrderFactory $orderFactory,
        OrderCollectionFactory $orderCollectionFactory,
        OrderSearchResultInterfaceFactory $orderSearchResultInterfaceFactory
    ) {
        $this->orderFactory = $orderFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->searchResultFactory = $orderSearchResultInterfaceFactory;
    }

    // ... getById, save and delete methods listed above ...

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->orderCollectionFactory->create();

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
     * @return \Magenest\Affiliate\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $order = $this->orderFactory->create();
        $order->getResource()->load($order, $id);
        if (! $order->getId()) {
            return null;
        }
        return $order;
    }

    /**
     * Delete test by ID.
     */
    public function deleteById($id)
    {
        $order = $this->getById($id);
        $this->delete($order);
    }

    public function save(OrderInterface $order)
    {
        $order->getResource()->save($order);
        return $order;
    }

    public function delete(OrderInterface $order)
    {
        $order->getResource()->delete($order);
    }

    /**
     * @param int $orderId
     * @return \Magenest\Affiliate\Api\Data\OrderInterface
     */
    public function getByOrderId($orderId)
    {
        $order = $this->orderFactory->create();
        $order->getResource()->load($order, $orderId, "order_id");
        if (!$order->getId()) {
            return null;
        }
        return $order;
    }
}
