<?php
/**
 * Created by Magenest.
 * Author: Pham Quang Hau
 * Date: 12/08/2016
 * Time: 22:49
 */

namespace Magenest\Affiliate\Controller\Adminhtml\Withdraw;

class View extends \Magenest\Affiliate\Controller\Adminhtml\Withdraw
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $this->_coreRegistry->register('affiliate_withdraw_id', $id);
            /**
 * @var \Magento\Backend\Model\View\Result\Page $resultPage
*/
            $resultPage = $this->_initAction();
            $title = __('View Withdrawal');
            $resultPage->getConfig()->getTitle()->prepend($title);

            return $resultPage;
        } else {
            $this->messageManager->addError(__('This withdrawal no longer exists.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }
    }
}
