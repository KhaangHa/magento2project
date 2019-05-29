<?php

namespace Magenest\Staff\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;


class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;
        $installer->startSetup();

        //Install new database table
        //DIRECTOR TABL

//        MOVIE
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magenest_staff')
        )->addColumn(

            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ],
            'Id'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null, [
            'unsigned' => true,
            'nullable' => false],
            'Customer ID'
        )->addColumn(
            'nick_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'nick_name'
        )->addColumn(
            'type',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null, [
            'unsigned' => true,
            'nullable' => false],
            'Customer ID'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null, [
            'nullable' => false,

        ],
            'status'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null, [
            'nullable' => false,
            'default' =>
                \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
        ],
            'Created at'
        )->addIndex(
            $installer->getIdxName
            ('magenest_staff', ['nick_name']),
            ['nick_name']
        );
        $installer->getConnection()->createTable($table);


        $installer->endSetup();
    }

}