<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:21
 */
namespace Magenest\Affiliate\Model;



use Magento\Framework\Model\AbstractModel;

class Program extends AbstractModel
{

    const TYPE_PAY_PER_CLICK = 1;
    const TYPE_PAY_PER_SALE = 2;
    const TYPE_PAY_PER_LEAD = 3;
    const TYPE_DISCOUNT = 4;


    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 2;

    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\ResourceModel\Program');
        $this->setIdFieldName('id');
    }
}