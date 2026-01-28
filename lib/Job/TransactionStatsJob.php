<?php
namespace OCA\ClubSuiteFinance\Job;

use OCP\BackgroundJob\TimedJob;

class TransactionStatsJob extends TimedJob {
    public function __construct() { parent::__construct(); }
    public function run($argument) {
        // aggregate balances, warm caches
    }
}
