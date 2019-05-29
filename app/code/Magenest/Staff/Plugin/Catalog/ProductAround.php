<?php
namespace Magenest\Staff\Plugin\Catalog;
use Magento\Catalog\Model\Product;
//use Magenest\Staff\Helper\CustomerId;
use Magento\Catalog\Block\Product\Price;
class ProductAround
{

    public function getCustomerId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerSession = $objectManager->create("Magento\Customer\Model\Session");

        if ($customerSession->isLoggedIn()) {
            return $customerSession->getCustomerId();
        }
        return "";
    }

    public function afterGetProductPrice($subject, $result, $product)
    {
        $id = $this->getCustomerId();
        if($id == "")
            return $result;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        $sql = "Select type FROM magenest_staff WHERE customer_id = " . $id;
        $outPut = $connection->fetchAll($sql);

        if($outPut){
            $logger = \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class);
            $logger->info($result);

            $subscriptionTextHTML = '<b class="price">(lv'.$outPut[0]['type'].')</b>';

            return $result.$subscriptionTextHTML;
        }
        return $result;
    }

}