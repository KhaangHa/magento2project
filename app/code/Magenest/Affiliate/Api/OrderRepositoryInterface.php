<?php

namespace Magenest\Affiliate\Api;

use Magenest\Affiliate\Api\Data\OrderInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface OrderRepositoryInterface
 *
 * @api
 */
interface OrderRepositoryInterface
{
    /**
     * @param int $id
     * @return \Magenest\Affiliate\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Delete test by ID.
     */
    public function deleteById($id);

    /**
     * @param \Magenest\Affiliate\Api\Data\OrderInterface $order
     * @return \Magenest\Affiliate\Api\Data\OrderInterface
     */
    public function save(OrderInterface $order);

    /**
     * @param \Magenest\Affiliate\Api\Data\OrderInterface $order
     * @return void
     */
    public function delete(OrderInterface $order);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magenest\Affiliate\Api\Data\OrderSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $orderId
     * @return \Magenest\Affiliate\Api\Data\OrderInterface
     */
    public function getByOrderId($orderId);
}
