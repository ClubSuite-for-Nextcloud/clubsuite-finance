<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

/**
 * Migration: Create tables for transactions and categories
 * © 2026 Stefan Schulz – Alle Rechte vorbehalten
 */
class Version000000Date20260111000001 extends SimpleMigrationStep {

	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		// Categories Table
		if (!$schema->hasTable('cs_finance_cats')) {
			$table = $schema->createTable('cs_finance_cats');
			$table->addColumn('id', 'integer', [
				'autoincrement' => true,
				'notnull' => true,
			]);
			$table->addColumn('name', 'string', [
				'notnull' => true,
				'length' => 128,
			]);
			$table->addColumn('color', 'string', [
				'notnull' => false,
				'length' => 32,
			]);
			$table->addColumn('created_by', 'string', [
				'notnull' => false,
				'length' => 64,
			]);
			$table->setPrimaryKey(['id']);
		}

		// Transactions Table
		if (!$schema->hasTable('cs_finance_trans')) {
			$table = $schema->createTable('cs_finance_trans');
			$table->addColumn('id', 'integer', [
				'autoincrement' => true,
				'notnull' => true,
			]);
			$table->addColumn('date', 'date', [
				'notnull' => true,
			]);
			$table->addColumn('amount', 'decimal', [
				'notnull' => true,
				'scale' => 2,
				'precision' => 12,
			]);
			$table->addColumn('category_id', 'integer', [
				'notnull' => false,
			]);
			$table->addColumn('description', 'string', [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('type', 'string', [
				'notnull' => true, // 'income' or 'expense'
				'length' => 16,
			]);
			$table->addColumn('created_by', 'string', [
				'notnull' => false,
				'length' => 64,
			]);
			$table->addColumn('created_at', 'datetime', [
				'notnull' => false,
			]);
			
			$table->setPrimaryKey(['id']);
			$table->addIndex(['date'], 'cs_fin_trans_date');
			$table->addIndex(['category_id'], 'cs_fin_trans_cat');
		}

		return $schema;
	}
}
