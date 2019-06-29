<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 10/03/2018
 * Time: 14:50
 */
namespace Magenest\Affiliate\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;

class Delete extends Action
{
    protected $campaignFactory;

    public function __construct(
        Action\Context $context,
        \Magenest\Affiliate\Model\BannerFactory $bannerFactory
    ) {
        parent::__construct($context);
        $this->campaignFactory = $bannerFactory;
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        $data = $this->getRequest()->getParams();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data['id']) {
            try {
                $model = $this->campaignFactory->create();
                $model->load($data['id']);
                $model->delete();
                $this->messageManager->addSuccess(__('Banner deleted'));
                return $resultRedirect->setPath('*/*/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $data['id']]);
            }
        }
        $this->messageManager->addError(__('Banner does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}