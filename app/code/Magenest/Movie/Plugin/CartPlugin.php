<?php

namespace Magenest\Movie\Plugin;

use Magento\Framework\Exception\LocalizedException;

//use Magento\Catalog\Model\ResourceModel\Category\Flat;

class CartPlugin
{

    public function aroundGetItemData($subject, $proceed, $item)

    {

        $result = $proceed($item);
        $productId = $result['product_id'];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $configProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);

        $_children = $configProduct->getTypeInstance()->getUsedProducts($configProduct);
        $sku = $result['product_sku'];
        foreach ($_children as $child) {
            if($child['sku'] == $sku)
            {
                $result['product_image']['src'] = 'http://magento.local/pub/media/catalog/product/'.$child['image'];
                $result['product_name'] = $child['name'];
            }
        }
        return $result;

    }

}