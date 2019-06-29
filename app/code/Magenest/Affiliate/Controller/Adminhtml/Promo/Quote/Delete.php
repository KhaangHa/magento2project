<?php
///**
// * Copyright © Magento, Inc. All rights reserved.
// * See COPYING.txt for license details.
// */
//
//namespace Magenest\Affiliate\Controller\Adminhtml\Promo\Quote;
//
//class Delete extends \Magento\SalesRule\Controller\Adminhtml\Promo\Quote\Delete
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
//     * Delete promo quote action
//     *
//     * @return void
//     */
//    public function execute()
//    {
//        $id = $this->getRequest()->getParam('id');
//
//        $affiliateRuleId = $this->_configHelper->getRuleId();
//        if ($id === $affiliateRuleId) {
//            $this->messageManager->addError(
//                __('We can\'t delete the rule right now. It\'s Affiliate Program Coupon')
//            );
//            $this->_redirect('sales_rule/*/edit', ['id' => $this->getRequest()->getParam('id')]);
//            return;
//        }
//        if ($id) {
//            try {
//                $model = $this->_objectManager->create(\Magento\SalesRule\Model\Rule::class);
//                $model->load($id);
//                $model->delete();
//                $this->messageManager->addSuccess(__('You deleted the rule.'));
//                $this->_redirect('sales_rule/*/');
//                return;
//            } catch (\Magento\Framework\Exception\LocalizedException $e) {
//                $this->messageManager->addError($e->getMessage());
//            } catch (\Exception $e) {
//                $this->messageManager->addError(
//                    __('We can\'t delete the rule right now. Please review the log and try again.')
//                );
//                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
//                $this->_redirect('sales_rule/*/edit', ['id' => $this->getRequest()->getParam('id')]);
//                return;
//            }
//        }
//        $this->messageManager->addError(__('We can\'t find a rule to delete.'));
//        $this->_redirect('sales_rule/*/');
//    }
//}
