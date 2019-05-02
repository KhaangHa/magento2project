<?php

namespace Packt\UiComponent\Controller\Adminhtml\News;

use Packt\UiComponent\Controller\Adminhtml\News;

class Index extends News
{
    /**
     * @return void
     */
    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Packt_UiComponent::main_menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Simple News'));

        return $resultPage;
    }
}
