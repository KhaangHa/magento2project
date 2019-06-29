<?php

namespace Magenest\Staff\Controller\Result;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;


class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $resultJsonFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    )
    {

        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }

    public function getCustomerId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerSession = $objectManager->create("Magento\Customer\Model\Session");

        if ($customerSession->isLoggedIn()) {
            return $customerSession->getCustomerId();
        }
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $level = $this->getRequest()->getParam('level');
        $name = $this->getRequest()->getParam('name');
        $id = $this->getCustomerId();
        if($level != '' && $name != '')
        {
            echo "<script>alert('true');</script>";
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();

            $sql = "UPDATE magenest_staff 
                SET type = " . $level . ", nick_name = '" . $name .
                "' WHERE customer_id = " . $id;
            $connection->query($sql);
        }
        else
            echo "<script>alert('false');</script>";

//        return $resultRedirect->setPath('staff');

    }
}