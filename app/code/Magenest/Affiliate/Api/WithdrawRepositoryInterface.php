<?php

namespace Magenest\Affiliate\Api;

use Magenest\Affiliate\Api\Data\WithdrawInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface WithdrawRepositoryInterface
 *
 * @api
 */
interface WithdrawRepositoryInterface
{
    /**
     * @param int $id
     * @return \Magenest\Affiliate\Api\Data\WithdrawInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Delete test by ID.
     */
    public function deleteById($id);

    /**
     * @param \Magenest\Affiliate\Api\Data\WithdrawInterface $withdraw
     * @return \Magenest\Affiliate\Api\Data\WithdrawInterface
     */
    public function save(WithdrawInterface $withdraw);

    /**
     * @param \Magenest\Affiliate\Api\Data\WithdrawInterface $withdraw
     * @return void
     */
    public function delete(WithdrawInterface $withdraw);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magenest\Affiliate\Api\Data\WithdrawSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @return \Magenest\Affiliate\Model\ResourceModel\Withdraw\Collection
     */
    public function getCollection();
}
