<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Events;

use OCP\EventDispatcher\Event;
use OCA\ClubSuiteFinance\Db\TransactionEntity;

class TransactionCreatedEvent extends Event {
    private TransactionEntity $transaction;

    public function __construct(TransactionEntity $transaction) {
        parent::__construct();
        $this->transaction = $transaction;
    }

    public function getTransaction(): TransactionEntity {
        return $this->transaction;
    }
}
