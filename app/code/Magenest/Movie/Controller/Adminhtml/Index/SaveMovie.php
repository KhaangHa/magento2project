<?php

namespace Magenest\Movie\Controller\Adminhtml\Index;

class SaveMovie extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Index';

    protected $resultPageFactory;
    protected $contactFactory;
    protected $actorFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\Movie\Model\MovieSubscriptionFactory $contactFactory
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

                $contact = $this->contactFactory->create();

                $data = array_filter($data, function ($value) {
                    return $value !== '';
                });

                $this->_eventManager->dispatch(
                    'adminhtml_movie_save_after',
                    ['movie'=>$data]
                );

                if(isset($data['rating']))
                {
                    $num = $data['rating'];
                    if($num<=0)
                    {
                        $this->messageManager->addError(__('Rating must be greater than 0'));
                        $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                        return $resultRedirect->setPath('*/*/newmovie');
                    }
                }
                $data['rating'] = 0;
                $contact->setData($data);

                $contact->save();
                $this->messageManager->addSuccess(__('Successfully saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                return $resultRedirect->setPath('*/*/movie', ['id' => $contact->getId()]);
            }
        }

        return $resultRedirect->setPath('*/*/movie');
    }
}