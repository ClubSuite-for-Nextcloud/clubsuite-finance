<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

/**
 * Migration: Add member_id to transactions
 * © 2026 Stefan Schulz – Alle Rechte vorbehalten
 */
class Version000000Date20260112000001 extends SimpleMigrationStep {

	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if ($schema->hasTable('cs_finance_trans')) {
			$table = $schema->getTable('cs_finance_trans');
            
            if (!$table->hasColumn('member_id')) {
                $table->addColumn('member_id', 'string', [
                    'notnull' => false,
                    'length' => 64,
                ]);
            }
		}

		return $schema;
	}
}
