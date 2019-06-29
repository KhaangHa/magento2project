<?php

namespace Magenest\Affiliate\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{

    protected $_programFactory;
    protected $connection;
    protected $resource;
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->connection = $resource->getConnection();
        $this->resource = $resource;
    }

    public function insertMultiple($table, $data)
    {
        try {
            $tableName = $this->resource->getTableName($table);
            return $this->connection->insertMultiple($tableName, $data);
        } catch (\Exception $e) {
            //Error
        }
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.3.11', '<')) {
            $data = [
                [
                    'name' => "Pay Per Click",
                ],
                [
                    'name' => "Pay Per Sale",
                ],
                [
                    'name' => "Pay Per Lead",
                ],
                [
                    'name' => "Pay Per Discount",
                ]];

            $this->insertMultiple('magenest_affiliate_program_type',$data);


        }

        if (version_compare($context->getVersion(), '1.3.13', '<')) {
            $data = [
                [
                    'name' => "Percent Of Order Amount",
                    'status' => 1
                ],
                [
                    'name' => "Fixed Amount",
                    'status' => 1
                ]
                ];

            $this->insertMultiple('magenest_affiliate_program_commission_type',$data);


        }

        $installer->endSetup();
    }
}
