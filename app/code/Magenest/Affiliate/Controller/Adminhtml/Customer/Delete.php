<?php
/**
 * Created by PhpStorm.
 * User: chung
 * Date: 6/19/17
 * Time: 11:13 PM
 */

namespace Magenest\Affiliate\Controller\Adminhtml\Customer;

use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    protected $customerReporitory;

    public function __construct(
        Action\Context $context,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerReporitory = $customerRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->customerReporitory->deleteById($id);
                $this->messageManager->addSuccessMessage(__("Deleted"));
                return $this->_redirect('affiliate/customer/index');
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __("Error"));
            }
        }

        $this->messageManager->addErrorMessage(__("Can't specify customer"));
        return $this->_redirect('affiliate/customer/index');
    }
}
