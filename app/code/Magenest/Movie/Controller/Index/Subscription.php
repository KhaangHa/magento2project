<?php

namespace Magenest\Movie\Controller\Index;

class Subscription extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $subscription = $this->_objectManager->create('Magenest\Movie\Model\MovieSubscription');
        $subscription->setName('Sasukeee');
        $subscription->setDescription('OK');
        $subscription->setRating(0);
        $subscription->setDirector_id(1);
        $subscription->save();
        $this->getResponse()->setBody('success');
    }
}