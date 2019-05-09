<?php

namespace Magenest\Movie\Controller\Adminhtml\Index;

class SaveDirector extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Index';

    protected $resultPageFactory;
    protected $contactFactory;
    protected $actorFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\Movie\Model\SubscriptionFactory $contactFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->contactFactory = $contactFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            try {

                $id = $data['director_id'];
                $contact = $this->contactFactory->create()->load($id);


                $data = array_filter($data, function ($value) {
                    return $value !== '';
                });

                $contact->setData($data);
                $contact->save();
                $this->messageManager->addSuccess(__('Successfully saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                return $resultRedirect->setPath('*/*/director', ['id' => $contact->getId()]);
            }
        }

        return $resultRedirect->setPath('*/*/index');
    }
}