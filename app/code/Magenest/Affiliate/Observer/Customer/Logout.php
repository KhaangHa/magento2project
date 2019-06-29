<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 07/03/2018
 * Time: 15:44
 */
namespace Magenest\Affiliate\Observer\Customer;

use Magenest\Affiliate\Cookie\Cookie;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Logout implements ObserverInterface
{
    protected $cookie;

    protected $cookieManager;

    protected $_logger;

    protected $_cookieMetadataFactory;

    public function __construct(
        \Magenest\Affiliate\Cookie\Cookie $cookie,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Psr\Log\LoggerInterface $logger

    ) {
        $this->cookie = $cookie;
        $this->cookieManager = $cookieManager;
        $this->_logger = $logger;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->cookieMetadataFactory = $cookieMetadataFactory;

    }
    public function execute(Observer $observer)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration(0);
        $time = 0;
        $durent =abs(
            round(
                intval(
                   $time
                    )
                )
            );
        $this->cookie->set(null,$durent);
    }
}