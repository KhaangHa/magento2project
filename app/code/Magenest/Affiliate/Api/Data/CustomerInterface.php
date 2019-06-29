<?php

namespace Magenest\Affiliate\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface CustomerInterface
 *
 * @api
 */
interface CustomerInterface extends ExtensibleDataInterface
{
    public function getData($key = '', $index = null);

    public function setData($key, $value = null);
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magenest\Affiliate\Api\Data\CustomerExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param  \Magenest\Affiliate\Api\Data\CustomerExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magenest\Affiliate\Api\Data\CustomerExtensionInterface $extensionAttributes
    );

    /**
     * @return string
     */
    public function getCustomerId();

    /**
     * @param string $customerId
     * @return void
     */
    public function setCustomerId($customerId);

    public function getUniqueCode();

    public function setUniqueCode($code);

    public function getBalance();

    public function setBalance($balance);

    public function getTotalCommission();

    public function setTotalCommission($commission);

    public function getTotalPaid();

    public function setTotalPaid($commission);

    public function getPaypalEmail();

    public function setPaypalEmail($paypalEmail);

    public function getBankAccount();

    public function setBankAccount($bankAccount);

    public function getBankName();

    public function setBankName($bankName);

    public function getStatus();

    public function setStatus($status);

    /**
     * @return string
     */
    public function getCreatedTime();

    /**
     * @return string
     */
    public function getUpdatedTime();

    public function isAffiliate();
}
