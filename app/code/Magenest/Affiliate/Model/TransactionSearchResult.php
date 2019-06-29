<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 05/08/2017
 * Time: 08:23
 */

namespace Magenest\Affiliate\Model;

use Magento\Framework\Api\SearchResults;
use Magenest\Affiliate\Api\Data\TransactionSearchResultInterface;

class TransactionSearchResult extends SearchResults implements TransactionSearchResultInterface
{
}
