<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 17:20
 */

namespace Magenest\Affiliate\Model;

use Magenest\Affiliate\Api\Data\CustomerInterface;
use Magenest\Affiliate\Api\Data\CustomerExtensionInterface;
use Magenest\Affiliate\Helper\Constant;

class Customer extends \Magento\Framework\Model\AbstractExtensibleModel implements CustomerInterface
{
    const STATUS_DIABLE = 3;
    const STATUS_APPROVED = 2;
    protected function _construct()
    {
        $this->_init(ResourceModel\Customer::class);
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->_getData("customer_id");
    }

    /**
     * @param string $name
     * @return void
     */
    public function setCustomerId($customerId)
    {
        $this->setData("customer_id", $customerId);
    }

    public function getUniqueCode()
    {
        return $this->_getData("unique_code");
    }

    public function setUniqueCode($code)
    {
        $this->setData("unique_code", $code);
    }

    public function getBalance()
    {
        return $this->_getData("balance");
    }

    public function setBalance($balance)
    {
        $this->setData("balance", $balance);
    }

    public function getTotalCommission()
    {
        return $this->_getData("total_commission");
    }

    public function setTotalCommission($commission)
    {
        $this->setData("total_commission", $commission);
    }

    public function getPaypalEmail()
    {
        return $this->_getData("paypal_email");
    }

    public function setPaypalEmail($paypalEmail)
    {
        $this->setData("paypal_email", $paypalEmail);
    }

    public function getBankAccount()
    {
        return $this->_getData("bank_account");
    }

    public function setBankAccount($bankAccount)
    {
        $this->setData('bank_account', $bankAccount);
    }

    public function getBankName()
    {
        return $this->_getData("bank_name");
    }

    public function setBankName($bankName)
    {
        $this->setData('bank_name', $bankName);
    }
    public function getStatus()
    {
        return $this->_getData("status");
    }

    public function setStatus($status)
    {
        $this->setData("status", $status);
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

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magenest\Affiliate\Api\Data\CustomerExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(CustomerExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }

    public function getTotalPaid()
    {
        return $this->_getData("total_withdraw");
    }

    public function setTotalPaid($totalWithdraw)
    {
        return $this->setData("total_withdraw", $totalWithdraw);
    }

    public function isAffiliate()
    {
        $status = $this->getStatus();
        if ($status == Constant::AFFILIATE_CUSTOMER_APPROVED) {
            return true;
        } else {
            return false;
        }
    }
}
