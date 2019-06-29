<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 10/03/2018
 * Time: 14:50
 */

namespace Magenest\Affiliate\Controller\Adminhtml\Campaign;

use Magento\Backend\App\Action;

class Save extends Action
{

    protected $campaignFactory;

    public function __construct(
        Action\Context $context,
        \Magenest\Affiliate\Model\CampaignFactory $campaignFactory
    ) {
        parent::__construct($context);
        $this->campaignFactory =        $campaignFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->campaignFactory->create();
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
                if ($id != $model->getId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Wrong mapping rule.'));
                }
            }
            else unset($data['id']);
            $model->setData($data);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The campaign has been saved.'));
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