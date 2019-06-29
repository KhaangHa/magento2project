<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 04/08/2017
 * Time: 17:20
 */

namespace Magenest\Affiliate\Model;

use Magenest\Affiliate\Api\Data\DownlineInterface;
use Magenest\Affiliate\Api\Data\DownlineExtensionInterface;
use Magento\Framework\Model\AbstractModel;
class Downline extends \Magento\Framework\Model\AbstractExtensibleModel implements DownlineInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Downline::class);
    }


    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Magenest\Affiliate\Api\Data\DownlineExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param  \Magenest\Affiliate\Api\Data\DownlineExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Magenest\Affiliate\Api\Data\DownlineExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * @return string
     */
    public function getCreatedTime()
    {
        return $this->_getData("created_at");
    }

    /**
     * @return string
     */
    public function getUpdatedTime()
    {
        return $this->_getData("updated_at");
    }
}
