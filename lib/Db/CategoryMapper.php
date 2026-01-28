<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;

class CategoryMapper extends QBMapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'clubsuite_categories', Category::class);
    }

    public function findAll(): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
           ->from('clubsuite_categories')
           ->orderBy('name', 'ASC');
        return $this->findEntities($qb);
    }

    public function findById(int $id): Category {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
           ->from('clubsuite_categories')
           ->where($qb->expr()->eq('id', $qb->createNamedParameter($id)));
        return $this->findEntity($qb);
    }
}
