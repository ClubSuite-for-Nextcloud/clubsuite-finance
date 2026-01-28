<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;

class AccountMapper extends QBMapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'clubsuite_accounts', Account::class);
    }

    public function findAll(): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
           ->from('clubsuite_accounts')
           ->orderBy('name', 'ASC');
        return $this->findEntities($qb);
    }

    public function findById(int $id): Account {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
           ->from('clubsuite_accounts')
           ->where($qb->expr()->eq('id', $qb->createNamedParameter($id)));
        return $this->findEntity($qb);
    }
}
