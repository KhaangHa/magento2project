<?php

namespace Magenest\Affiliate\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface WithdrawSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Magenest\Affiliate\Api\Data\WithdrawInterface[]
     */
    public function getItems();

    /**
     * @param \Magenest\Affiliate\Api\Data\WithdrawInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
