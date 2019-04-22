<?php
namespace Packt\MyModule\Block;
use Magento\Framework\View\Element\Template;
class LandingStation extends Template
{
    public function getLandingsUrl()
    {
        return $this->getUrl('mymodule/index/landing');
    }

    public function getMainUrl()
    {
        return $this->getUrl('mymodule/index/index');
    }
}