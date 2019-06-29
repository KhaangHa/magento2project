<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 10/03/2018
 * Time: 14:50
 */
namespace Magenest\Affiliate\Controller\Adminhtml\Program;

use Magento\Backend\App\Action;

class Delete extends Action
{
    protected $_programFactory;

    public function __construct(
        Action\Context $context,
        \Magenest\Affiliate\Model\ProgramFactory $programFactory
    ) {
        parent::__construct($context);
        $this->_programFactory = $programFactory;
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        $data = $this->getRequest()->getParams();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data['id']) {
            try {
                $model = $this->_programFactory->create();
                $model->load($data['id']);
                $model->delete();
                $this->messageManager->addSuccess(__('Program deleted'));
                return $resultRedirect->setPath('*/*/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $data['id']]);
            }
        }
        $this->messageManager->addError(__('Program does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}