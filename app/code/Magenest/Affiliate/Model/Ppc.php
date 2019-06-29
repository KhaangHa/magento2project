<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 14:21
 */
namespace Magenest\Affiliate\Model;



use Magento\Framework\Model\AbstractModel;

class Ppc extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Magenest\Affiliate\Model\ResourceModel\Ppc');
        $this->setIdFieldName('id');
    }
}