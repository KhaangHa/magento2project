<?php

namespace Magenest\Affiliate\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface TransactionSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Magenest\Affiliate\Api\Data\TransactionInterface[]
     */
    public function getItems();

    /**
     * @param \Magenest\Affiliate\Api\Data\TransactionInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
