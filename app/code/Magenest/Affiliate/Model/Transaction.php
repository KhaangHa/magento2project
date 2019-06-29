<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 17:20
 */

namespace Magenest\Affiliate\Model;

use Magenest\Affiliate\Api\Data\TransactionInterface;
use Magenest\Affiliate\Api\Data\TransactionExtensionInterface;

class Transaction extends \Magento\Framework\Model\AbstractExtensibleModel implements TransactionInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Transaction::class);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magenest\Affiliate\Api\Data\TransactionExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param  \Magenest\Affiliate\Api\Data\TransactionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magenest\Affiliate\Api\Data\TransactionExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * @return string
     */
    public function getCreatedTime()
    {
        return $this->_getData("created_at");
    }

    /**
     * @return string
     */
    public function getUpdatedTime()
    {
        return $this->_getData("updated_at");
    }

    public function getCommissionMoney()
    {
        return $this->_getData("receive_money");
    }

    public function getSubtractMoney()
    {
        return $this->_getData("subtract_money");
    }

    public function getCountDown()
    {
        return $this->_getData("count_down");
    }

    public function getDescription()
    {
        return $this->_getData("description");
    }

    public function getDownline()
    {
        return $this->_getData("customer_id_downline");
    }
}
