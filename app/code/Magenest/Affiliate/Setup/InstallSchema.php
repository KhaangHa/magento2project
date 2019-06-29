<?php

namespace Magenest\Affiliate\Setup;

use Magenest\Affiliate\Helper\Constant;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * schema table
     */
    const TABLE_AFFILIATE_CUSTOMER = 'magenest_affiliate_customer';
    const TABLE_AFFILIATE_DOWNLINE = 'magenest_affiliate_downline';
    const TABLE_AFFILIATE_TRANSACTION = 'magenest_affiliate_transaction';
    const TABLE_AFFILIATE_WITHDRAW = 'magenest_affiliate_withdraw';
    const TABLE_AFFILIATE_ORDER = 'magenest_affiliate_order';

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $installer->getConnection()->dropTable($installer->getTable(self::TABLE_AFFILIATE_CUSTOMER));
        $installer->getConnection()->dropTable($installer->getTable(self::TABLE_AFFILIATE_TRANSACTION));
        $installer->getConnection()->dropTable($installer->getTable(self::TABLE_AFFILIATE_WITHDRAW));
        $installer->getConnection()->dropTable($installer->getTable(self::TABLE_AFFILIATE_DOWNLINE));
        $installer->getConnection()->dropTable($installer->getTable(self::TABLE_AFFILIATE_ORDER));

        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::TABLE_AFFILIATE_CUSTOMER))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'primary key'
            )->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => false],
                'Customer Id'
            )->addColumn(
                'unique_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                20,
                [],
                'Affiliate customer Unique code'
            )->addColumn(
                'balance',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                null,
                [
                    'precision' => 12,
                    'scale' => 2,
                    'nullable' => false,
                    'default' => '0.00'
                ],
                'Account Balance'
            )->addColumn(
                'total_commission',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                null,
                [
                    'precision' => 12,
                    'scale' => 2,
                    'nullable' => false,
                    'default' => '0.00'
                ],
                'Total Commission'
            )->addColumn(
                'total_withdraw',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                null,
                [
                    'precision' => 12,
                    'scale' => 2,
                    'nullable' => false,
                    'default' => '0.00'
                ],
                'Total Withdraw'
            )
            ->addColumn(
                'paypal_email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Paypal Email'
            )
            ->addColumn(
                'bank_account',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Bank Account'
            )
            ->addColumn(
                'bank_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Bank Name'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false, 'default' => Constant::AFFILIATE_CUSTOMER_PENDING],
                'status'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )
            ->addIndex(
                $installer->getIdxName(self::TABLE_AFFILIATE_CUSTOMER, ['customer_id']),
                ['customer_id']
            )
            ->addIndex(
                $installer->getIdxName(self::TABLE_AFFILIATE_CUSTOMER, ['unique_code']),
                ['unique_code']
            )
            ->setComment('Affiliate account table');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::TABLE_AFFILIATE_TRANSACTION))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Primary key'
            )->addColumn(
                'customer_id_upline',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => false],
                'Customer Id Upline'
            )->addColumn(
                'customer_id_downline',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Customer Id Downline'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => false],
                'Order id'
            )->addColumn(
                'invoice_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => true],
                'invoice id'
            )->addColumn(
                'creditmemo_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => true],
                'credit memo id'
            )->addColumn(
                'count_down',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false],
                'Day holding count down'
            )->addColumn(
                'receive_money',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                null,
                [
                    'precision' => 12,
                    'scale' => 2,
                    'nullable' => false,
                    'default' => '0.00'
                ],
                'receive money'
            )->addColumn(
                'subtract_money',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                null,
                [
                    'precision' => 12,
                    'scale' => 2,
                    'nullable' => false,
                    'default' => '0.00'
                ],
                'subtract money'
            )->addColumn(
                'description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                200,
                [],
                'Description'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )
            ->setComment('Affiliate transaction table');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::TABLE_AFFILIATE_WITHDRAW))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Primary key'
            )->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => false],
                'Customer Id'
            )->addColumn(
                'money',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                null,
                [
                    'precision' => 12,
                    'scale' => 2,
                    'nullable' => false,
                    'default' => '0.00'
                ],
                'withdraw money'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'status'
            )->addColumn(
                'method',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Method'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )
            ->setComment('Affiliate withdraw table');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::TABLE_AFFILIATE_DOWNLINE))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Primary key'
            )->addColumn(
                'customer_id_upline',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true],
                'Customer Id Upline'
            )->addColumn(
                'customer_id_downline',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true,'nullable' => false],
                'Customer Id Downline'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )
            ->setComment('Affiliate downline table');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable(self::TABLE_AFFILIATE_ORDER))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Primary key'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false],
                'Order id'
            )->addColumn(
                'data',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'order data'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )
            ->addIndex(
                $installer->getIdxName(self::TABLE_AFFILIATE_ORDER, ['order_id']),
                ['order_id']
            )
            ->setComment('Affiliate order info table');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
