<?php
namespace Packt\HelloWorld\Controller\Index;
class Subscription extends \Magento\Framework\App\Action\Action {
    public function execute()
    {
        $firstName = $this->getRequest()->getParam('firstname');
        $lastName = $this->getRequest()->getParam('lastname');
        $Email = $this->getRequest()->getParam('email');
        $Message = $this->getRequest()->getParam('message');

        $subscription = $this->_objectManager->create('Packt\HelloWorld\Model\Subscription');
        $subscription->setFirstname($firstName);
        $subscription->setLastname($lastName);
        $subscription->setEmail($Email);
        $subscription->setMessage($Message);
        $subscription->save();
        $this->getResponse()->setBody('success');
    }
}