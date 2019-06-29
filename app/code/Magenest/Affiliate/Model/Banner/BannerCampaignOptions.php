<?php
namespace Magenest\Affiliate\Model\Banner;

class BannerCampaignOptions implements \Magento\Framework\Option\ArrayInterface
{
    protected $_campaignFactory;

    public function __construct(
        \Magenest\Affiliate\Model\ResourceModel\Campaign\CollectionFactory $campaignFactory,
        array $data = []
    )
    {
        $this->_campaignFactory = $campaignFactory;
    }

    public function toOptionArray()
    {
        $collection = $this->_campaignFactory->create();
        $collection->load();
        $campaignCollection = $collection->getData();
        $n = count($campaignCollection);

        for($i=0;$i<$n;$i++)
        {
            $outPut[$i]['value'] = (int)$campaignCollection[$i]['id'];
            $outPut[$i]['label'] = $campaignCollection[$i]['name'];
        }
        return $outPut;
    }
}