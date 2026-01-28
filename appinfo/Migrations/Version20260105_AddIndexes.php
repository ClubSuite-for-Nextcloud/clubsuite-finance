<?php
namespace OCA\ClubSuiteFinance\Migrations;

use OCP\AppFramework\Db\SchemaTrait;
use OCP\Migration\IMigration;
use OCP\Migration\IOutput;

class Version20260105_AddIndexes implements IMigration {
    use SchemaTrait;

    public function changeSchema(IOutput $output) {
        $schema = $this->getSchema();
        if ($schema->hasTable('finanzen_transaction')) {
            $t = $schema->getTable('finanzen_transaction');
            if (!$t->hasIndex('idx_finanzen_transaction_category')) {
                $t->addIndex(['category_id'], 'idx_finanzen_transaction_category');
            }
            if (!$t->hasIndex('idx_finanzen_transaction_date')) {
                $t->addIndex(['date'], 'idx_finanzen_transaction_date');
            }
        }
    }

    public function up(IOutput $output) {
        $this->changeSchema($output);
    }

    public function down(IOutput $output) {
        // no-op for now
    }
}
