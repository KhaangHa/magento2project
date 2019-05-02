<?php
namespace Packt\HelloWorld\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class AddNew extends \Magento\Backend\App\Action
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}