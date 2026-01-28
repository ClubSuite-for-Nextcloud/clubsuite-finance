<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;

class TransactionMapper extends QBMapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'clubsuite_transactions', Transaction::class);
    }

    public function findAll(): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
           ->from('clubsuite_transactions')
           ->orderBy('date', 'DESC');
        return $this->findEntities($qb);
    }

    public function findById(int $id): Transaction {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
           ->from('clubsuite_transactions')
           ->where($qb->expr()->eq('id', $qb->createNamedParameter($id)));
        return $this->findEntity($qb);
    }

    public function findByAccount(int $accountId): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
           ->from('clubsuite_transactions')
           ->where($qb->expr()->eq('account_id', $qb->createNamedParameter($accountId)))
           ->orderBy('date', 'DESC');
        return $this->findEntities($qb);
    }

    public function findByMember(int $memberId): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
           ->from('clubsuite_transactions')
           ->where($qb->expr()->eq('member_id', $qb->createNamedParameter($memberId)))
           ->orderBy('date', 'DESC');
        return $this->findEntities($qb);
    }
}
