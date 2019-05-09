<?php
namespace Magenest\Movie\Helper;
use Magento\Framework\App\Bootstrap;
use Magento\Test\Integrity\Phrase\AbstractTestCase;

class ConfigHelper extends AbstractTestCase
{

    public function testFoo()
    {
        $this->setConfig('salesforce/moviepage/row_in_tables', 0);


    }

    private function setConfig($path, $value)
    {
        /** @var \Magento\Config\Model\ResourceModel\Config $model */
        $model = $this->getObjectManager()->create('Magento\Config\Model\ResourceModel\Config');
        $model->saveConfig($path, $value, 'default', 0);

        /** @var \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList */
        $cacheTypeList = $this->getObjectManager()->create('Magento\Framework\App\Cache\TypeListInterface');
        $cacheTypeList->cleanType('config');
    }

    /** @return \Magento\Framework\ObjectManagerInterface */
    protected function getObjectManager()
    {
        return (new Magento\Framework\App\Bootstrap)->getObjectManager();
    }

}