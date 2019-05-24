<?php

namespace Magenest\Movie\Observer;

use Magento\Framework\Event\ObserverInterface;

class ObserveMovie implements ObserverInterface
{
    protected $logger;
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $movie = $observer->getData('movie');

        return $this;
    }
}