<?php

namespace Magenest\Affiliate\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface OrderInterface
 *
 * @api
 */
interface OrderInterface extends ExtensibleDataInterface
{
    public function getData($key = '', $index = null);

    public function setData($key, $value = null);

    public function getId();

    public function setId($id);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magenest\Affiliate\Api\Data\OrderExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param  \Magenest\Affiliate\Api\Data\OrderExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magenest\Affiliate\Api\Data\OrderExtensionInterface $extensionAttributes
    );

    /**
     * @return string
     */
    public function getCreatedTime();

    /**
     * @return string
     */
    public function getUpdatedTime();

    public function setOrderData($key, $value);

    /**
     * @param string $key
     * @return array|string
     */
    public function getOrderData($key = null);

    public function setOrderId($orderId);

    public function getOrderId($orderId);

    public function getCommissionValue();

    public function getCommissionHold();

    public function getSubtractValue();

    public function getUplineCustomerId();
}
