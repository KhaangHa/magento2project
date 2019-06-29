<?php

namespace Magenest\Affiliate\Controller\Adminhtml\Transaction;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;


class Save extends Action
{
    protected $adapterFactory;

    protected $transactionFactory;

    public function __construct(
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        Action\Context $context,
        \Magenest\Affiliate\Model\TransactionFactory $transactionFactory
    )
    {
        $this->adapterFactory = $adapterFactory;
        $this->transactionFactory = $transactionFactory;
        parent::__construct($context);

    }


    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $general = $this->getRequest()->getParams();
        $data = $general;
        if (isset($general['general'])) {
            $data['id'] = $general['general']['id'];
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        if ($data) {
            try {
                $model = $this->transactionFactory->create();
                $id = $data["id"];
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('Wrong mapping rule.'));
                    }
                }
                else unset($data["id"]); //remove id element so it will generate automatically

                $model->setData($data);
//                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());

                $model->save();
                $this->messageManager->addSuccess(__('The banner has been saved.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while saving the mapping.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }


}
