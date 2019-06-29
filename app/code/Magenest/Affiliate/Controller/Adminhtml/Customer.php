<?php
/**
 * Created by Magenest.
 * Author: Pham Quang Hau
 * Date: 12/08/2016
 * Time: 16:41
 */

namespace Magenest\Affiliate\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

abstract class Customer extends Action
{
    protected $_pageFactory;

    protected $_coreRegistry;

    public function __construct(
        Action\Context $context,
        PageFactory $pageFactory,
        Registry $registry
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    protected function _initAction()
    {
        /**
 * @var \Magento\Backend\Model\View\Result\Page $resultPage
*/
        $resultPage = $this->_pageFactory->create();
        $resultPage->setActiveMenu('Magenest_Affiliate::customer')
            ->addBreadcrumb(__('Affiliate Customer'), __('Affiliate Customer'));

        $resultPage->getConfig()->getTitle()->set(__('Affiliate Customer'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Affiliate::customer');
    }
}
