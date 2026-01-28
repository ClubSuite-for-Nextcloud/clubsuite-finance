<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;

class Category extends Entity implements JsonSerializable {

    protected $name;
    protected $createdAt;
    protected $updatedAt;

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public static function build(array $params): static {
        $entity = new static();
        $entity->setName($params['name']);
        return $entity;
    }
}
