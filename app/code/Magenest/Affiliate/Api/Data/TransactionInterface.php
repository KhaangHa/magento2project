<?php

namespace Magenest\Affiliate\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface TransactionInterface
 *
 * @api
 */
interface TransactionInterface extends ExtensibleDataInterface
{
    public function getData($key = '', $index = null);

    public function setData($key, $value = null);

    public function getId();

    public function setId($id);

    public function getCommissionMoney();

    public function getSubtractMoney();

    public function getCountDown();

    public function getDescription();

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magenest\Affiliate\Api\Data\TransactionExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param  \Magenest\Affiliate\Api\Data\TransactionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magenest\Affiliate\Api\Data\TransactionExtensionInterface $extensionAttributes
    );

    /**
     * @return string
     */
    public function getCreatedTime();

    /**
     * @return string
     */
    public function getUpdatedTime();

    public function getDownline();
}
