<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 10/03/2018
 * Time: 14:51
 */
namespace Magenest\Affiliate\Controller\Adminhtml\Campaign;

use Magento\Backend\App\Action;

class MassDelete extends Action
{
    protected $campaignFactory;

    protected $_filter;

    public function __construct(
        Action\Context $context,
        \Magenest\Affiliate\Model\CampaignFactory $campaignFactory,
        \Magento\Ui\Component\MassAction\Filter   $filter
    ) {
        parent::__construct($context);
        $this->campaignFactory = $campaignFactory;
        $this->_filter = $filter;
    }

    public function execute()
    {
        $feedbackCollection = $this->campaignFactory->create()->getCollection();
        $collections = $this->_filter->getCollection($feedbackCollection);
        $totals = 0;
        try {

            foreach ($collections as $item) {

                /*
                    * @var \Magenest\MultipleVendor\Model\VendorQuestion $item
                 */
                $item->delete();

                $totals++;
            }

            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $totals));
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*');

    }
}