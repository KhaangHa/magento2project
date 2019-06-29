<?php

namespace Magenest\Affiliate\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface WithdrawInterface
 *
 * @api
 */
interface WithdrawInterface extends ExtensibleDataInterface
{
    public function getData($key = '', $index = null);

    public function setData($key, $value = null);

    public function getId();

    public function setId($id);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magenest\Affiliate\Api\Data\WithdrawExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param  \Magenest\Affiliate\Api\Data\WithdrawExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magenest\Affiliate\Api\Data\WithdrawExtensionInterface $extensionAttributes
    );

    /**
     * @return string
     */
    public function getCreatedTime();

    /**
     * @return string
     */
    public function getUpdatedTime();

    public function getStatus();

    public function setStatus($status);

    public function getMethod();

    public function getMoney();

    public function getCustomerId();
}
