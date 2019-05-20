<?php

namespace Magenest\Movie\Setup;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Config;
class UpgradeData implements UpgradeDataInterface
{
    private $customerSetupFactory, $attributeSetFactory, $eavSetupFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig       = $eavConfig;
    }


    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.19')) {
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $customerSetup->addAttribute(Customer::ENTITY, 'avatar_customer', [
                'label' => 'Customer image',
                'type' => 'varchar',
                'input' => 'image',
                'backend' => 'Magenest\Movie\Model\Customer\Attribute\Backend\File',
                'frontend' => '',
                'required' => false,
                'visible' => true,
                'user_defined' => false,
                'position' => 5,
            ]);
            $tax_exe = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'avatar_customer')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'note' => 'Please wait for changes to be made',
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]);
            $tax_exe->save();
        }

        if (version_compare($context->getVersion(), '2.1.10')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $eavSetup->addAttribute(Customer::ENTITY, 'avatar_customer', [
                'label' => 'Customer image',
                'type' => 'varchar',
                'input' => 'image',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => 0,
                'position' => 5,
            ]);
            $eavSetup->addAttributeToSet(
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
                null,
                'avatar_customer'
            );
            $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'avatar_customer')
                ->addData([
                    'used_in_forms' => ['adminhtml_customer', 'customer_account_edit', 'customer_account_create'],
                ])->save();

        }

        if (version_compare($context->getVersion(), '2.1.4') < 0) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                'sample_attribute',
                [
                    'type'         => 'varchar',
                    'label'        => 'Sample Attribute',
                    'input'        => 'text',
                    'required'     => false,
                    'visible'      => true,
                    'user_defined' => true,
                    'position'     => 999,
                    'system'       => 0,
                ]
            );
            $sampleAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'sample_attribute');

            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            $sampleAttribute->setData(
                'used_in_forms',
                ['adminhtml_customer'],['customer_account_edit']

            );
            $sampleAttribute->save();
        }
    }

}