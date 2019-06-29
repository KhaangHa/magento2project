<?php
//
///**
// * Copyright © Magento, Inc. All rights reserved.
// * See COPYING.txt for license details.
// */
//
//namespace Magenest\Affiliate\Controller\Adminhtml\Promo\Quote;
//
//class CouponsMassDelete extends \Magento\SalesRule\Controller\Adminhtml\Promo\Quote\CouponsMassDelete
//{
//    protected $_configHelper;
//
//    public function __construct(
//        \Magento\Backend\App\Action\Context $context,
//        \Magento\Framework\Registry $coreRegistry,
//        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
//        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
//        \Magenest\Affiliate\Helper\ConfigHelper $configHelper
//    ) {
//        $this->_configHelper = $configHelper;
//        parent::__construct($context, $coreRegistry, $fileFactory, $dateFilter);
//    }
//
//    /**
//     * Coupons mass delete action
//     *
//     * @return void
//     */
//    public function execute()
//    {
//        $this->_initRule();
//        $rule = $this->_coreRegistry->registry(\Magento\SalesRule\Model\RegistryConstants::CURRENT_SALES_RULE);
//
//        if (!$rule->getId()) {
//            $this->_forward('noroute');
//        }
//        $id = $rule->getRuleId();
//        $affiliateRuleId = $this->_configHelper->getRuleId();
//        if ($id === $affiliateRuleId) {
//            $this->messageManager->addError(
//                __('We can\'t delete these coupon right now. It\'s Affiliate Program Coupon.')
//            );
//            $this->_redirect('sales_rule/*/edit', ['id' => $this->getRequest()->getParam('id')]);
//            return;
//        }
//
//        $codesIds = $this->getRequest()->getParam('ids');
//
//        if (is_array($codesIds)) {
//            $couponsCollection = $this->_objectManager->create(
//                \Magento\SalesRule\Model\ResourceModel\Coupon\Collection::class
//            )->addFieldToFilter(
//                'coupon_id',
//                ['in' => $codesIds]
//            );
//
//            foreach ($couponsCollection as $coupon) {
//                $coupon->delete();
//            }
//        }
//    }
//}
