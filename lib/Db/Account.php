<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;

class Account extends Entity implements JsonSerializable {

    protected $name;
    protected $type;
    protected $createdAt;
    protected $updatedAt;

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public static function build(array $params): static {
        $entity = new static();
        $entity->setName($params['name']);
        $entity->setType($params['type']);
        // Dates are usually handled by mapper or DB defaults, but can be set here if passed
        return $entity;
    }
}
