<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Service;

use OCA\ClubSuiteFinance\Db\Category;
use OCA\ClubSuiteFinance\Db\CategoryMapper;
use OCA\ClubSuiteFinance\Exception\NotFoundException;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

class CategoryService {

    private CategoryMapper $mapper;

    public function __construct(CategoryMapper $mapper) {
        $this->mapper = $mapper;
    }

    public function listCategories(): array {
        return $this->mapper->findAll();
    }

    public function createCategory(string $name): Category {
        $category = new Category();
        $category->setName($name);
        return $this->mapper->insert($category);
    }

    public function updateCategory(int $id, string $name): Category {
        try {
            $category = $this->mapper->findById($id);
            $category->setName($name);
            return $this->mapper->update($category);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
             throw new NotFoundException($e->getMessage());
        }
    }

    public function deleteCategory(int $id): void {
        try {
            $category = $this->mapper->findById($id);
            $this->mapper->delete($category);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
             throw new NotFoundException($e->getMessage());
        }
    }
}
