<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/11/16
 * Time: 10:00
 */

namespace Magenest\Affiliate\Helper;

class Constant
{
    const AFFILIATE_CUSTOMER_PENDING = 1;
    const AFFILIATE_CUSTOMER_APPROVED = 2;
    const AFFILIATE_CUSTOMER_DISABLED = 3;

    const AFFILIATE_COOKIE_UID = "magenest_affiliate_uid";

    public static function getCustomerAffiliateStatus($status)
    {
        if ($status == self::AFFILIATE_CUSTOMER_PENDING) {
            return "Pending";
        }
        if ($status == self::AFFILIATE_CUSTOMER_APPROVED) {
            return "Approved";
        }
        if ($status == self::AFFILIATE_CUSTOMER_DISABLED) {
            return "Disabled";
        }
        return "null";
    }

    const AFFILIATE_WITHDRAW_PENDING = 0;
    const AFFILIATE_WITHDRAW_APPROVED = 1;
    const AFFILIATE_WITHDRAW_DECLINED = 2;
    const AFFILIATE_WITHDRAW_ERROR = 3;
    const AFFILIATE_WITHDRAW_CANCEL = 4;


    public static function getWithdrawAffiliateStatus($status)
    {
        if ($status == self::AFFILIATE_WITHDRAW_PENDING) {
            return "Pending";
        }
        if ($status == self::AFFILIATE_WITHDRAW_APPROVED) {
            return "Approved";
        }
        if ($status == self::AFFILIATE_WITHDRAW_DECLINED) {
            return "Declined";
        }if ($status == self::AFFILIATE_WITHDRAW_ERROR) {
            return "Error";
        }
        if ($status == self::AFFILIATE_WITHDRAW_CANCEL) {
            return "Canceled";
        }
        return "null";
    }
}
