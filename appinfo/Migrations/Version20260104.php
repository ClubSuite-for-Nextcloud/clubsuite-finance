<?php
namespace OCA\ClubSuiteFinance\Migrations;

use Doctrine\DBAL\Schema\Schema;
use OCP\Migration\IChange;

class Version20260104 implements IChange {
    public function changeSchema(Schema $schema): void {
        if (!$schema->hasTable('finanzen_transaction')) {
            $table = $schema->createTable('finanzen_transaction');
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('date', 'datetime');
            $table->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2]);
            $table->addColumn('type', 'string', ['length' => 16]);
            $table->addColumn('category_id', 'integer', ['notnull' => false]);
            $table->addColumn('description', 'text', ['notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => false]);
            $table->setPrimaryKey(['id']);
        }

        if (!$schema->hasTable('finanzen_category')) {
            $table = $schema->createTable('finanzen_category');
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('name', 'string', ['length' => 255]);
            $table->setPrimaryKey(['id']);
        }
    }

    public function getComment(): string { return 'Create finanzen tables'; }
}
