<?php
/**
 * Created by PhpStorm.
 * User: ninhvu
 * Date: 09/03/2018
 * Time: 08:18
 */
namespace Magenest\Affiliate\Block\Adminhtml;

use Magento\Backend\Block\Template;

class AffiliateDiscount extends Template
{
    protected $orderAffliateFactory;

    public function __construct(
        Template\Context $context,
        array $data = [],
        \Magenest\Affiliate\Model\OrderFactory $orderAffiliateFactory

    ){
        parent::__construct($context, $data);
        $this->orderAffliateFactory = $orderAffiliateFactory;
    }

    public function getDiscountAffiliate()
    {
        $id = $this->getRequest()->getParam('order_id');
        $orderAffiliate = $this->orderAffliateFactory->create()->getCollection()
            ->addFieldToFilter('order_id', $id)->getFirstItem();
        // echeck exist order affliliate
        if ($orderAffiliate->getData('id')) {
            // Show discount
            $orderId = $orderAffiliate['order_id'];
            $dataAffiliateOrder= unserialize($orderAffiliate['data']);
            $affiliateDiscount = $dataAffiliateOrder['affiliate_discount']['total'];
            $affiliateDiscount = number_format($affiliateDiscount, 2, '.', '');
            return $affiliateDiscount;
        }
        return null;
    }
}