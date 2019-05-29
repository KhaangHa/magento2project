<?php

namespace Magenest\Staff\Setup;

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
        if (version_compare($context->getVersion(), '2.0.1')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $eavSetup->addAttribute(Customer::ENTITY, 'staff_type', [
                'label' => 'Staff type',
                'type' => 'int',
                'input' => 'select',
                'required' => false,
                'visible' => false,
                'system' => 0,
                'position' => 25,
            ]);
            $eavSetup->addAttributeToSet(
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
                null,
                'staff_type'
            );
            $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'staff_type')
                ->addData([
                    'used_in_forms' => ['adminhtml_customer', 'customer_account_edit', 'customer_account_create'],
                ])->save();

        }

    }

}