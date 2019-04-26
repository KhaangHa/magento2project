<?php

namespace Packt\MyModule\Block\Adminhtml;

class Subscription extends \Magento\Backend\Block\Widget\Grid\Container
{
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/additem');
    }
    protected function _construct()
    {
            $this->_blockGroup = 'Packt_MyModule';
            $this->_controller = 'adminhtml_subscription';
            parent::_construct();
    }
}