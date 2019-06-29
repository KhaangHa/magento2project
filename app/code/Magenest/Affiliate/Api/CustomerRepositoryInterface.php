<?php

namespace Magenest\Affiliate\Api;

use Magenest\Affiliate\Api\Data\CustomerInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface CustomerRepositoryInterface
 *
 * @api
 */
interface CustomerRepositoryInterface
{
    /**
     * @param int $id
     * @return \Magenest\Affiliate\Api\Data\CustomerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Delete test by ID.
     */
    public function deleteById($id);

    /**
     * @param \Magenest\Affiliate\Api\Data\CustomerInterface $customer
     * @return \Magenest\Affiliate\Api\Data\CustomerInterface
     */
    public function save(CustomerInterface $customer);

    /**
     * @param \Magenest\Affiliate\Api\Data\CustomerInterface $customer
     * @return void
     */
    public function delete(CustomerInterface $customer);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magenest\Affiliate\Api\Data\CustomerSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);


    public function getCollection();

    /**
     * @param $customerId
     * @return \Magenest\Affiliate\Api\Data\CustomerInterface
     */
    public function getByCustomerId($customerId);

    /**
     * @param $code
     * @return \Magenest\Affiliate\Api\Data\CustomerInterface
     */
    public function getByUniqueCode($code);

    /**
     * @return \Magenest\Affiliate\Api\Data\CustomerInterface[]
     */
    public function getListItem();
}
