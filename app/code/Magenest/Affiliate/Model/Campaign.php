<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:21
 */
namespace Magenest\Affiliate\Model;



use Magento\Framework\Model\AbstractModel;

class Campaign extends AbstractModel
{
    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 2;
    const TYPE_DISCOUNT = 1;
    const TYPE_PAY_PER_SALE = 2;
    const TYPE_PAY_PER_CLICK = 3;
    const TYPE_PAY_PER_LEAD = 4;
    const TYPE_PAY_PER_INPRESSION = 5;
    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\ResourceModel\Campaign');
        $this->setIdFieldName('id');
    }
}
