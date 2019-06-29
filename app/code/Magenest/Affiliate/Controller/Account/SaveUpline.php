<?php
namespace Magenest\Affiliate\Controller\Account;

use Magento\Framework\App\ResponseInterface;

class SaveUpline extends \Magenest\Affiliate\Controller\Account
{

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        if (!$this->_customerSession->isLoggedIn()) {
            /**
             * @var \Magento\Framework\Controller\Result\Redirect $resultRedirect
             */


            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }

//        $uplineId = $this->getRequest()->getParam('id');
//        $customerId = $this->getRequest()->getParam('customerId');
//        if($uplineId){
//            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//            $model = $objectManager->create('Magenest\Affiliate\Model\DownlineRepository')->load($customerId,'customer_id_downline')->getData();
//            $model->setCustomer_id_upline($uplineId);
//            $model['customer_id_upline'] = $uplineId;
//            $model->save();

//        }
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->set(__('Affiliate Dashboard'));

        return $this->_pageFactory->create();
    }
}