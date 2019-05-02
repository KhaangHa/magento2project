<?php

namespace Packt\UiComponent\Controller\Adminhtml\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Collection extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $productCollection = $this->_objectManager
            ->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
            ->setPageSize(10, 1);
        $output = '';
        foreach ($productCollection as $product) {
            $output .= \Zend_Debug::dump($product->debug(), null, false);
        }
        $this->getResponse()->setBody($output);
    }
}