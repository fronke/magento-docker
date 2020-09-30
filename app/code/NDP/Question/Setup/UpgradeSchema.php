<?php

namespace NDP\Question\Setup;


use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $dbVersion = $context->getVersion();

        if (version_compare($dbVersion, '1.0.3', '<')) {

            $installer = $setup;
            $connection = $installer->getConnection();

            $connection->addColumn(
                $installer->getTable('question'),
                'priority',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => false,
                    'default' => 0,
                    'comment' => 'Order priority questions'
                ]
            );

        }

        $setup->endSetup();
    }

}