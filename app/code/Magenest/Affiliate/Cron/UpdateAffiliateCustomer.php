<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 07/08/2017
 * Time: 17:05
 */

namespace Magenest\Affiliate\Cron;

class UpdateAffiliateCustomer
{
    protected $customerRepository;
    protected $eventManager;

    public function __construct(
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Event\Manager $eventManager
    ) {
        $this->customerRepository = $customerRepository;
        $this->eventManager = $eventManager;
    }

    public function execute()
    {
        $isCron = true;
        $customerLists = $this->customerRepository->getListItem();
        foreach ($customerLists as $customer) {
            $customerId = $customer->getCustomerId();
            $this->eventManager->dispatch(
                'magenest_affiliate_update_balance',
                [
                    'customer_id'=>$customerId,
                    'is_cron' => $isCron
                ]
            );
        }
    }
}
