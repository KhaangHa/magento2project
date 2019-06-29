<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 17:20
 */

namespace Magenest\Affiliate\Model;

use Magenest\Affiliate\Api\Data\WithdrawInterface;
use Magenest\Affiliate\Api\Data\WithdrawExtensionInterface;

class Withdraw extends \Magento\Framework\Model\AbstractExtensibleModel implements WithdrawInterface
{
    const PAYMENT_METHOD_PAYPAL = "PAYPAL";
    const PAYMENT_METHOD_OFFLINE = "OFFLINE";
    protected function _construct()
    {
        $this->_init(ResourceModel\Withdraw::class);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magenest\Affiliate\Api\Data\WithdrawExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param  \Magenest\Affiliate\Api\Data\WithdrawExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magenest\Affiliate\Api\Data\WithdrawExtensionInterface $extensionAttributes
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

    public function getStatus()
    {
        return $this->_getData("status");
    }

    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }

    public function getMethod()
    {
        return $this->_getData("method");
    }

    public function getMoney()
    {
        return $this->_getData("money");
    }

    public function getCustomerId()
    {
        return $this->_getData("customer_id");
    }
}
