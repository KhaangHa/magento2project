<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 19:48
 */

namespace Magenest\Affiliate\Controller\Account;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NotFoundException;

class Transaction extends \Magenest\Affiliate\Controller\Account
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
        if (!$this->getIsAffiliate()) {
            throw new NotFoundException(__('Some Exception message.'));
        }
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->set(__('Transactions History'));
        return $this->_pageFactory->create();
    }
}
