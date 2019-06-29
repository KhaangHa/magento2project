<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 19/08/2017
 * Time: 10:53
 */

namespace Magenest\Affiliate\Controller\Adminhtml\Withdraw;

use Magenest\Affiliate\Helper\Constant;

class Decline extends \Magenest\Affiliate\Controller\Adminhtml\Withdraw
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('withdraw_id');
        $withdraw = $this->withdrawRepository->getById($id);
        if ($withdraw) {
            $withdraw->setData('status', Constant::AFFILIATE_WITHDRAW_DECLINED);
            $this->withdrawRepository->save($withdraw);
            $this->messageManager->addNoticeMessage("Withdrawal was decline");
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/view', ["id"=>$withdraw->getId()]);
        } else {
            $this->messageManager->addErrorMessage("Error exception");
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }
    }
}
