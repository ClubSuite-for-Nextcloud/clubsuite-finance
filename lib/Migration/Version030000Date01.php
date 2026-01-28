<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

class Version030000Date01 extends SimpleMigrationStep {

    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        // A) oc_clubsuite_accounts
        if (!$schema->hasTable('clubsuite_accounts')) {
            $table = $schema->createTable('clubsuite_accounts');
            $table->addColumn('id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('name', 'string', [
                'notnull' => true,
                'length' => 150,
            ]);
            $table->addColumn('type', 'string', [
                'notnull' => true,
                'length' => 20,
            ]);
            $table->addColumn('created_at', 'datetime', [
                'notnull' => false,
            ]);
            $table->addColumn('updated_at', 'datetime', [
                'notnull' => false,
            ]);
            $table->setPrimaryKey(['id']);
        }

        // B) oc_clubsuite_categories
        if (!$schema->hasTable('clubsuite_categories')) {
            $table = $schema->createTable('clubsuite_categories');
            $table->addColumn('id', 'integer', [
                'autoincrement' => true, 
                'notnull' => true,
            ]);
            $table->addColumn('name', 'string', [
                'notnull' => true,
                'length' => 150,
            ]);
            $table->addColumn('created_at', 'datetime', [
                'notnull' => false,
            ]);
            $table->addColumn('updated_at', 'datetime', [
                'notnull' => false,
            ]);
            $table->setPrimaryKey(['id']);
        }

        // C) oc_clubsuite_transactions
        if (!$schema->hasTable('clubsuite_transactions')) {
            $table = $schema->createTable('clubsuite_transactions');
            $table->addColumn('id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('account_id', 'integer', [
                'notnull' => true,
            ]);
            $table->addColumn('category_id', 'integer', [
                'notnull' => false,
            ]);
            $table->addColumn('member_id', 'integer', [
                'notnull' => false,
            ]);
            $table->addColumn('amount', 'integer', [
                'notnull' => true,
            ]);
            $table->addColumn('date', 'date', [
                'notnull' => true,
            ]);
            $table->addColumn('purpose', 'text', [
                'notnull' => false,
            ]);
            $table->addColumn('created_at', 'datetime', [
                'notnull' => false,
            ]);
            $table->addColumn('updated_at', 'datetime', [
                'notnull' => false,
            ]);
            $table->setPrimaryKey(['id']);
            // Indexes for foreign keys (optional but good practice)
            $table->addIndex(['account_id'], 'idx_cs_fin_acc');
            $table->addIndex(['category_id'], 'idx_cs_fin_cat');
            $table->addIndex(['member_id'], 'idx_cs_fin_mem');
        }

        return $schema;
    }
}
