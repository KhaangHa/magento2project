<?php

namespace Magenest\Movie\Controller\Index;

class Subscription extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $subscription = $this->_objectManager->create('Magenest\Movie\Model\Subscription');
        $subscription->setName('Sasukeee');
        $subscription->save();
        $this->getResponse()->setBody('success');
    }
}