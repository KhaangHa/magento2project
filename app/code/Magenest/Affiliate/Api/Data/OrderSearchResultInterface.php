<?php

namespace Magenest\Affiliate\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface OrderSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Magenest\Affiliate\Api\Data\OrderInterface[]
     */
    public function getItems();

    /**
     * @param \Magenest\Affiliate\Api\Data\OrderInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
