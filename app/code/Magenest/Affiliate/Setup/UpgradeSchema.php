<?php

namespace Magenest\Affiliate\Setup;

use Magenest\Affiliate\Model\AffiliateDiscount;
use Magento\Backend\Block\Widget\Tab;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{


    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $this->createAffiliateDiscount($installer);
            $this->createAffiliateCampaign($installer);
        }
        if (version_compare($context->getVersion(), '1.3.1') < 0) {
            //1.0.2 add magenest_affiliate_banner table
            //1.0.5 fixed date time column issue
            //1.1.0 change table layout
            //1.2.0 re-custom banner table
            //1.2.2 set default val for click
            //1.3.0 re-custom banner table again
            $this->createAffiliateBanner($installer);
            $this->createAffiliatePpc($installer);
        }
        if (version_compare($context->getVersion(), '1.3.2') < 0) {
            $this->createAffiliateLog($installer);
        }
        if (version_compare($context->getVersion(), '1.3.4') < 0) {
            //1.3.2 add foreign key campaign to banner table
            $this->createAffiliateBanner($installer);
        }
        if (version_compare($context->getVersion(), '1.3.10') < 0) {
            $this->createAffiliateProgramType($installer);
            $this->createAffiliateProgram($installer);
        }
        if (version_compare($context->getVersion(), '1.3.11') < 0) {
            $this->createAffiliateProgramConfigCommission($installer);
        }
        if (version_compare($context->getVersion(), '1.3.12')<0){
            $this->createAffiliateProgramConfigCommissionType($installer);
        }
        if(version_compare($context->getVersion(),'1.3.13')<0)
        {
            $this->createAffiliateProgramConfigCommissionType($installer);
            $this->createAffiliateCommissionByType($installer);
        }
        $installer->endSetup();
    }

    private function createAffiliateDiscount($installer)
    {
        $tableName = 'magenest_affiliate_discount';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'type',
                Table::TYPE_TEXT,
                25,
                ['nullable' => false],
                "Type:Config or Campaign"
            )
            ->addColumn(
                'simple_action',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Simpler Action'
            )
            ->addColumn(
                'discount_amount',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                '25',
                ['unsigned' => true, 'nullable' => false,],
                'Amount'
            )
            ->addColumn(
                'create_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Create At'
            )
            ->addColumn(
                'update_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Update At'
            );
        $installer->getConnection()->createTable($table);


    }

    public function createAffiliateCampaign($installer)
    {
        $tableName = 'magenest_affiliate_campaign';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'product_ids',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false,],
                'Products ID'
            )
            ->addColumn(
                'affiliate_discount_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false,],
                'Affiliate Discount ID'
            )
            ->addColumn(
                'status',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'Status Campaign'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                100,
                ['nullable' => false],
                'Name campaign'
            )
            ->addColumn(
                'type',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'Type campaign'
            )
            ->addColumn(
                'description',
                Table::TYPE_TEXT,
                255,
                [],
                'Description'
            )
            ->addColumn(
                'start_time',
                Table::TYPE_DATE,
                null,
                [],
                'Time start'
            )
            ->addColumn(
                'end_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                [],
                'Time end'
            )->addColumn(
                'create_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Create At'
            )
            ->addColumn(
                'update_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Update At'
            );
        $installer->getConnection()->createTable($table);
    }

    public function createAffiliateBanner($installer)
    {
        $tableName = 'magenest_affiliate_banner';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addcolumn(
                'title',
                Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Title'
            )
            ->addcolumn(
                'link',
                Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Link'
            )
            ->addColumn(
                'image',
                Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Banner'
            )
            ->addColumn(
                'number_of_click',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 0],
                'Click'
            )
            ->addColumn(
                'click_raw',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 0],
                'Click raw'
            )
            ->addColumn(
                'click_unique',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 0],
                'Click unique'
            )
            ->addColumn(
                'click_unique_commission',
                Table::TYPE_FLOAT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 0],
                'Click unique commission'
            )
            ->addColumn(
                'click_raw_commission',
                Table::TYPE_FLOAT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 0],
                'Click raw commission'
            )
            ->addColumn(
                'type',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Type'
            )
            ->addColumn(
                'expense',
                Table::TYPE_FLOAT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 0],
                'Expense'
            )
            ->addColumn(
                'status',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Status'
            )
            ->addColumn(
                'width',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Width'
            )
            ->addColumn(
                'height',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Height'
            )
            ->addColumn(
                'create_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Create At'
            )->addColumn(
                'campaign_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Campaing ID'
            )->addForeignKey(
                $installer->getFkName('magenest_affiliate_banner', 'campaign_id', 'magenest_affiliate_campaign', 'id'),
                'campaign_id',
                $installer->getTable('magenest_affiliate_campaign'),
                'id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
        $installer->getConnection()->createTable($table);
    }

    public function createAffiliatePpc($installer)
    {
        $tableName = 'magenest_affiliate_ppc';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'ppc_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'PPC Affiliate ID'
            )
            ->addColumn(
                'banner_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Banner Id'
            )
            ->addColumn(
                'unique_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                150,
                ['nullable' => false],
                'unique_code'
            )
            ->addColumn(
                'customer_ip',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                150,
                ['nullable' => false],
                'Customer IP'
            )
            ->addColumn(
                'is_unique',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is Unique'
            )
            ->addColumn(
                'commission',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL, ('12,4'),
                ['nullable' => false, 'default' => '0.0000'],
                'Commission'
            )
            ->addColumn(
                'base_currency_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Base Currency Code'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->setComment(
                'Affiliate - PPC Table'
            );
        $installer->getConnection()->createTable($table);
    }

    public function createAffiliateLog($installer)
    {
        $tableName = 'magenest_affiliate_log';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Log ID'
            )
            ->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Type'
            )
            ->addColumn(
                'description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Description'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->setComment(
                'Affiliate - Log Table'
            );
        $installer->getConnection()->createTable($table);
    }

    public function createAffiliateProgramType($installer)
    {
        $tableName = 'magenest_affiliate_program_type';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Program Type ID'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Name'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->setComment(
                'Affiliate - Program Type Table'
            );
        $installer->getConnection()->createTable($table);
    }

    public function createAffiliateProgram($installer)
    {
        $tableName = 'magenest_affiliate_program';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Program ID'
            )
            ->addColumn(
                'program_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program Type ID'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Name'
            )
            ->addColumn(
                'description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Description'
            )
            ->addColumn(
                'status',
                Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'Status Program'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->setComment(
                'Affiliate - Program Table'
            );
        $installer->getConnection()->createTable($table);
    }

    public function createAffiliateProgramConfigCommission($installer)
    {
        $tableName = 'magenest_affiliate_program_config_commission';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'program_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program ID'
            )
            ->addColumn(
                'program_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program type ID'
            )
            ->addColumn(
                'tier',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program tier'
            )
            ->addColumn(
                'commission',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                null,
                ['nullable' => false, 'unsigned' => true, 'precision' => 12,
                    'scale' => 2],
                'Program Commission'
            );
        $installer->getConnection()->createTable($table);
    }
    public function createAffiliateProgramConfigCommissionType($installer)
    {
        $tableName = 'magenest_affiliate_program_commission_type';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program ID'
            )
            ->addColumn(
                'description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program type ID'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program tier'
            )
            ->addColumn(
                'create_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Create At'
            );
        $installer->getConnection()->createTable($table);
    }
    public function createAffiliateCommissionByType($installer)
    {
        $tableName = 'magenest_affiliate_program_commission_by_type';
        if ($installer->tableExists($tableName)) {
            $installer->getConnection()->dropTable($tableName);
        }

        $table = $installer->getConnection()
            ->newTable($installer->getTable($tableName))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'program_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program ID'
            )
            ->addColumn(
                'program_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program type ID'
            )
            ->addColumn(
                'commission_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Commission type ID'
            )
            ->addColumn(
                'tier',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Program tier'
            )
            ->addColumn(
                'commission',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                null,
                ['nullable' => false, 'unsigned' => true, 'precision' => 12,
                    'scale' => 2],
                'Program Commission'
            );
        $installer->getConnection()->createTable($table);
    }
}
