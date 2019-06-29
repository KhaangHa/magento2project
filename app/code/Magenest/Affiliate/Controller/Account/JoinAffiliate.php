<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 21:07
 */

namespace Magenest\Affiliate\Controller\Account;

class JoinAffiliate extends \Magenest\Affiliate\Controller\Account
{
    public function execute()
    {
        $result = $this->jsonFactory->create();
        if (1) {
            try {
                $customerId = $this->_customerSession->getCustomer()->getId();
                $customerIdUpline = null;
                $customerAffiliateStatus = $this->_configHelper->getAffiliateStatus();
                $this->affiliateManagement->joinAffiliate($customerId, $customerIdUpline, $customerAffiliateStatus);
                $this->_customerSession->unsetUplineTemp();
                return $result->setData(
                    [
                    'success' => true,
                    'code'=>$customerAffiliateStatus
                    ]
                );
            } catch (\Exception $exception) {
                return $result->setData(
                    [
                    'success' => false
                    ]
                );
            }
        }
        return $result->setData(
            [
            'success' => false
            ]
        );
    }
}
