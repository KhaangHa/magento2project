<?php

namespace Magenest\Affiliate\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CustomerSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Magenest\Affiliate\Api\Data\CustomerInterface[]
     */
    public function getItems();

    /**
     * @param \Magenest\Affiliate\Api\Data\CustomerInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
