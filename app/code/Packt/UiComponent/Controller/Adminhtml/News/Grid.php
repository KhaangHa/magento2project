<?php

namespace Packt\UiComponent\Controller\Adminhtml\News;

use Packt\UiComponent\Controller\Adminhtml\News;

class Grid extends News
{
    /**
     * @return void
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        return  $resultPage;
    }
}
