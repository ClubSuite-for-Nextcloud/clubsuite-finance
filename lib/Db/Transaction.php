<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;

class Transaction extends Entity implements JsonSerializable {

    protected $accountId;
    protected $categoryId;
    protected $memberId;
    protected $amount;
    protected $date;
    protected $purpose;
    protected $createdAt;
    protected $updatedAt;

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'accountId' => $this->accountId,
            'categoryId' => $this->categoryId,
            'memberId' => $this->memberId,
            'amount' => $this->amount,
            'date' => $this->date,
            'purpose' => $this->purpose,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public static function build(array $params): static {
        $entity = new static();
        $entity->setAccountId((int)$params['accountId']);
        $entity->setCategoryId(isset($params['categoryId']) ? (int)$params['categoryId'] : null);
        $entity->setMemberId(isset($params['memberId']) ? (int)$params['memberId'] : null);
        $entity->setAmount((int)$params['amount']);
        $entity->setDate($params['date']);
        $entity->setPurpose($params['purpose']);
        return $entity;
    }
}
