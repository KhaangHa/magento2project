<?php

namespace Magenest\Affiliate\Api;

use Magenest\Affiliate\Api\Data\TransactionInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface TransactionRepositoryInterface
 *
 * @api
 */
interface TransactionRepositoryInterface
{
    /**
     * @param int $id
     * @return \Magenest\Affiliate\Api\Data\TransactionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Delete test by ID.
     */
    public function deleteById($id);

    /**
     * @param \Magenest\Affiliate\Api\Data\TransactionInterface $transaction
     * @return \Magenest\Affiliate\Api\Data\TransactionInterface
     */
    public function save(TransactionInterface $transaction);

    /**
     * @param \Magenest\Affiliate\Api\Data\TransactionInterface $transaction
     * @return void
     */
    public function delete(TransactionInterface $transaction);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magenest\Affiliate\Api\Data\TransactionSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $invoiceId
     * @return \Magenest\Affiliate\Api\Data\TransactionInterface
     */
    public function getByInvoiceId($invoiceId);

    /**
     * @param $orderId
     * @return float
     */
    public function getTotalCommissionByOrderId($orderId);

    /**
     * @param int $customerId
     * @return \Magenest\Affiliate\Api\Data\TransactionInterface[]
     */
    public function getListByUplineCustomerId($customerId);

    /**
     * @return \Magenest\Affiliate\Model\ResourceModel\Transaction\Collection
     */
    public function getCollection();
}
