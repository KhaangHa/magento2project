<?php
namespace Packt\MyModule\Controller\Index;
class Redirect extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $this->_redirect('mymodule');
    }
}