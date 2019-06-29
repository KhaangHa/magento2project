<?php

namespace Magenest\Affiliate\Api;

use Magenest\Affiliate\Api\Data\DownlineInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface DownlineRepositoryInterface
 *
 * @api
 */
interface DownlineRepositoryInterface
{
    /**
     * @param int $id
     * @return \Magenest\Affiliate\Api\Data\DownlineInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Delete test by ID.
     */
    public function deleteById($id);

    /**
     * @param \Magenest\Affiliate\Api\Data\DownlineInterface $downline
     * @return \Magenest\Affiliate\Api\Data\DownlineInterface
     */
    public function save(DownlineInterface $downline);

    /**
     * @param \Magenest\Affiliate\Api\Data\DownlineInterface $downline
     * @return void
     */
    public function delete(DownlineInterface $downline);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magenest\Affiliate\Api\Data\DownlineSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param int $customerId
     * @return \Magenest\Affiliate\Api\Data\DownlineInterface|bool
     */
    public function getByCustomerDownline($customerId);

    /**
     * @param int $customerId
     * @return int
     */
    public function findUplineCustomerId($customerId);
}
