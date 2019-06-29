<?php
namespace Magenest\Movie\Plugin;


class ConfigPlugin
{
    public function beforeSave(
        \Magento\Config\Model\Config $subject
    )
    {
        $data = $subject->getData('groups/moviepage/fields/text_field/value');
        if(strtolower($data) == 'pong')
            $subject->setDataByPath('salesforce/moviepage/text_field','Ping');
        return $subject;
    }
}
