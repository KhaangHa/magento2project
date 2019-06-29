<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 07/08/2017
 * Time: 13:58
 */

namespace Magenest\Affiliate\Observer\Affiliate;

use Magento\Framework\Event\ObserverInterface;

class UpdateCustomerBalance implements ObserverInterface
{
    protected $transactionRepository;
    protected $customerRepository;

    public function __construct(
        \Magenest\Affiliate\Api\TransactionRepositoryInterface $transactionRepository,
        \Magenest\Affiliate\Api\CustomerRepositoryInterface $customerRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->customerRepository = $customerRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerId = $observer->getData('customer_id');
        $isCron = $observer->getData('is_cron');
        $customer = $this->customerRepository->getByCustomerId($customerId);
        $currentBalance = $customer->getBalance();
        $currentTotalCommission = $customer->getTotalCommission();
        $transactions = $this->transactionRepository->getListByUplineCustomerId($customerId);
        foreach ($transactions as $transaction) {
            $countDown = $transaction->getCountDown();
            if ($isCron == true) {
                $countDown--;
                $transaction->setData('count_down', $countDown);
                $this->transactionRepository->save($transaction);
            }
            if ($countDown == "0") {
                $currentBalance+=$transaction->getCommissionMoney();
                $currentTotalCommission+=$transaction->getCommissionMoney();
                $currentBalance-=$transaction->getSubtractMoney();
                $countDown--;
                $transaction->setData('count_down', $countDown);
                $this->transactionRepository->save($transaction);
                $transactionDescription = $transaction->getDescription();
            }
        }
        $customer->setBalance($currentBalance);
        $customer->setTotalCommission($currentTotalCommission);
        $this->customerRepository->save($customer);

        //log type = 2
        //description email + has recieved commission of + $currentTotalCommission
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connectCustomer = $objectManager->create('\Magento\Customer\Model\Customer')->load($customerId);
        $emailCustomer = $connectCustomer->getEmail();

        $description = $emailCustomer . " has recieved a commission of $" . $currentTotalCommission . " from " . $transactionDescription;

        $connectLog = $objectManager->create('\Magenest\Affiliate\Model\Log');
        $connectLog->setType(2);
        $connectLog->setDescription($description);
        $connectLog->save();
    }
}
