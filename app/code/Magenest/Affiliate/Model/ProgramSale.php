<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:21
 */
namespace Magenest\Affiliate\Model;

use Magento\Framework\Model\AbstractModel;

class ProgramSale extends AbstractModel
{

    const TYPE_PERCENT_ORDER = 1;
    const TYPE_FIXED_PRICE = 2;

    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\ResourceModel\ProgramSale');
        $this->setIdFieldName('id');
    }
}