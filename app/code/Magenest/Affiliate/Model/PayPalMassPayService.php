<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 18/08/2017
 * Time: 19:00
 */

namespace Magenest\Affiliate\Model;

class PayPalMassPayService
{
    const PAYPAL_API_SANDBOX_URL = "https://api-3t.sandbox.paypal.com/nvp";
    const PAYPAL_API_LIVE_URL = "https://api-3t.paypal.com/nvp";
    const PAYPAL_RECEIVE_TYPE_EMAIL = "ReceiverEmail";
    const PAYPAL_RECEIVE_TYPE_PHONE = "ReceiverPhone";
    const PAYPAL_RECEIVE_TYPE_ID = "ReceiverID";
    const PAYPAL_RECEIVE_TYPE = [
        'ReceiverEmail' => 'ReceiverEmail',
        'ReceiverPhone' => 'ReceiverPhone',
        'ReceiverID' => 'ReceiverID'
    ];

    protected $zendHttpHelper;
    protected $configHelper;

    public function __construct(
        \Magenest\Affiliate\Helper\ZendHttpHelper $zendHttpHelper,
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper
    ) {
        $this->zendHttpHelper = $zendHttpHelper;
        $this->configHelper = $configHelper;
    }

    public function sendMassPayment($typeReceive, $addressReceive, $amount, $currency)
    {
        $url = $this->configHelper->getPayPalApiUrl();
        $receiveType = self::PAYPAL_RECEIVE_TYPE[$typeReceive];
        $amountSend = number_format($amount, 2, '.', '');
        $currencyCode = strtoupper($currency);
        $requestPost = [
            'METHOD' => 'MassPay',
            'VERSION' => '204.0',
            'USER' => $this->configHelper->getPayPalApiUsername(),
            'PWD' => $this->configHelper->getPayPalApiPassword(),
            'SIGNATURE' => $this->configHelper->getPayPalApiSignature(),
            'RECEIVERTYPE' => $receiveType,
            'L_EMAIL0' => $addressReceive,
            'L_AMT0' => $amountSend,
            'CURRENCYCODE0' => $currencyCode,
        ];
        $result = $this->zendHttpHelper->sendRequest($url, $requestPost, \Zend\Http\Request::METHOD_POST);
        return $result;
    }
}
