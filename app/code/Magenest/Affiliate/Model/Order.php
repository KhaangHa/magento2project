<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 17:20
 */

namespace Magenest\Affiliate\Model;

use Magenest\Affiliate\Api\Data\OrderInterface;
use Magenest\Affiliate\Api\Data\OrderExtensionInterface;

class Order extends \Magento\Framework\Model\AbstractExtensibleModel implements OrderInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Order::class);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magenest\Affiliate\Api\Data\OrderExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param  \Magenest\Affiliate\Api\Data\OrderExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magenest\Affiliate\Api\Data\OrderExtensionInterface $extensionAttributes
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

    public function setOrderData($key, $value)
    {
        $data = unserialize($this->_getData('data'));
        $data[$key] = $value;
        $this->setData('data', serialize($data));
    }

    /**
     * @param string $key
     * @return array|string
     */
    public function getOrderData($key = null)
    {
        $data = unserialize($this->_getData('data'));
        if (!!$key) {
            if (isset($data[$key])) {
                return $data[$key];
            } else {
                return false;
            }
        } else {
            return $data;
        }
    }

    public function setOrderId($orderId)
    {
        $this->setData("order_id", $orderId);
    }

    public function getOrderId($orderId)
    {
        return $this->_getData("order_id");
    }

    public function getCommissionValue()
    {
        $value = $this->getOrderData('commission_value');
        return $value?:0;
    }

    public function getCommissionHold()
    {
        $value = $this->getOrderData('commission_hold');
        return $value?:0;
    }

    public function getSubtractValue()
    {
        $value = $this->getOrderData('subtract_commission_value');
        return $value?:0;
    }

    public function getUplineCustomerId()
    {
        $value = $this->getOrderData('upline_customer_id');
        return $value?:null;
    }
}
