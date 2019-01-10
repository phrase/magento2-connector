<?php

namespace Memsource\Connector\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Memsource\Connector\Model\Mapping\Mapping;

class InstallSchema implements InstallSchemaInterface
{
    const TABLE_NAME = 'memsource_connector_translation_mapping';

    /**
     * @inheritdoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $this->createConfigTable($setup);
        $setup->endSetup();
    }

    /**
     * @param $installer SchemaSetupInterface
     * @return SchemaSetupInterface
     * @throws \Zend_Db_Exception
     */
    private function createConfigTable(SchemaSetupInterface $installer)
    {
        if ($installer->tableExists(self::TABLE_NAME) !== true) {
            $table = $installer
                ->getConnection()
                ->newTable($installer->getTable(self::TABLE_NAME))
                ->addColumn(
                    'map_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => false,
                    ]
                )
                ->addColumn(
                    Mapping::COLUMN_SOURCE_ID,
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable' => false,
                    ]
                )
                ->addColumn(
                    Mapping::COLUMN_TARGET_ID,
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable' => false,
                    ]
                )
                ->addColumn(
                    Mapping::COLUMN_TYPE,
                    Table::TYPE_TEXT,
                    20,
                    [
                        'nullable' => false,
                    ]
                )
                ->addColumn(
                    Mapping::COLUMN_LANG,
                    Table::TYPE_TEXT,
                    15,
                    [
                        'nullable' => false,
                    ]
                );

            $installer->getConnection()->createTable($table);
        }

        return $installer;
    }
}
