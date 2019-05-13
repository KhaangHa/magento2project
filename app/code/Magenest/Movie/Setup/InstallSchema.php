<?php

namespace Magenest\Movie\Setup;

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
            //DIRECTOR TABLE
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_director')
            )->addColumn(
                'director_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
                'Director Id'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                ['nullable' => false],
                'Name'
            );
            $installer->getConnection()->createTable($table);

//        MOVIE
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_movie')
            )->addColumn(

                'movie_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
                'Movie Id'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                ['nullable' => false],
                'Name'
            )->addColumn(
                'description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255, [
                'nullable' => false,

            ],
                'Description'
            )->addColumn(
                'rating',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'nullable' => false,

            ],
                'Rating'
            )->addColumn(
                'director_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'unsigned' => true,
                'nullable' => false],
                'Director ID'
            )->addIndex(
                $installer->getIdxName
                ('magenest_movie', ['name']),
                ['name']
            )->addForeignKey( // Add foreign key for table entity
                $installer->getFkName(
                    'magenest_movie', // New table
                    'director_id', // Column in New Table
                    'magenest_director', // Reference Table
                    'director_id' // Column in Reference table
                ),
                'director_id', // New table column
                $installer->getTable('magenest_director'), // Reference Table
                'director_id', // Reference Table Column
                // When the parent is deleted, delete the row with foreign key
                Table::ACTION_CASCADE
            )->setComment(
                'Duy Khang'
            );
            $installer->getConnection()->createTable($table);

//

//            ACTOR
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_actor')
            )->addColumn(
                'actor_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
                'Actor Id'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                ['nullable' => false],
                'Name'
            );
            $installer->getConnection()->createTable($table);

////            MOVIE ACTOR
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magenest_movie_actor')
            )->addColumn(
                'movie_actor_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
                'Id'
            )->addColumn(
                'movie_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true,
                    'nullable' => false],
                'Movie ID'
            )->addColumn(
                'actor_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true,
                    'nullable' => false],
                'Actor ID'
            )->addForeignKey( // Add foreign key for table entity
                $installer->getFkName(
                    'magenest_movie_actor', // New table
                    'movie_id', // Column in New Table
                    'magenest_movie', // Reference Table
                    'movie_id' // Column in Reference table
                ),
                'movie_id', // New table column
                $installer->getTable('magenest_movie'), // Reference Table
                'movie_id', // Reference Table Column
                // When the parent is deleted, delete the row with foreign key
                Table::ACTION_CASCADE
            )->addForeignKey( // Add foreign key for table entity
                $installer->getFkName(
                    'magenest_movie_actor', // New table
                    'actor_id', // Column in New Table
                    'magenest_actor', // Reference Table
                    'actor_id' // Column in Reference table
                ),
                'actor_id', // New table column
                $installer->getTable('magenest_actor'), // Reference Table
                'actor_id', // Reference Table Column
                // When the parent is deleted, delete the row with foreign key
                Table::ACTION_CASCADE
            );
            $installer->getConnection()->createTable($table);

            $installer->endSetup();
        }

}