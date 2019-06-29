<?php

namespace Magenest\Affiliate\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface DownlineSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Magenest\Affiliate\Api\Data\DownlineInterface[]
     */
    public function getItems();

    /**
     * @param \Magenest\Affiliate\Api\Data\DownlineInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
