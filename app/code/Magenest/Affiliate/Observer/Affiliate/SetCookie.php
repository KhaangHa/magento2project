<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 14/08/2017
 * Time: 10:49
 */


namespace Magenest\Affiliate\Observer\Affiliate;

use Magenest\Affiliate\Helper\Constant;
use Magento\Framework\Event\ObserverInterface;

class SetCookie implements ObserverInterface
{
    protected $cookie;
    protected $configHelper;

    public function __construct(
        \Magenest\Affiliate\Helper\ConfigHelper $configHelper,
        \Magenest\Affiliate\Cookie\Cookie $cookie
    ) {
        $this->cookie = $cookie;
        $this->configHelper = $configHelper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $uniqueCode = $observer->getData('unique_code');
        $duration = $this->configHelper->getCookieLifetime();
        $this->cookie->set($uniqueCode, $duration);
    }
}
