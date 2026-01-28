<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Service;

use OCA\ClubSuiteFinance\Db\Account;
use OCA\ClubSuiteFinance\Db\AccountMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCA\ClubSuiteFinance\Exception\NotFoundException;

class AccountService {

    private AccountMapper $mapper;

    public function __construct(AccountMapper $mapper) {
        $this->mapper = $mapper;
    }

    public function listAccounts(): array {
        return $this->mapper->findAll();
    }

    public function getAccount(int $id): Account {
        try {
            return $this->mapper->findById($id);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            throw new NotFoundException($e->getMessage());
        }
    }

    public function createAccount(string $name, string $type): Account {
        $account = new Account();
        $account->setName($name);
        $account->setType($type);
        // CreatedAt/UpdatedAt usually handled by DB or Mapper hooks, but setting here is fine too or relying on defaults
        return $this->mapper->insert($account);
    }

    public function updateAccount(int $id, string $name, string $type): Account {
        try {
            $account = $this->mapper->findById($id);
            $account->setName($name);
            $account->setType($type);
            return $this->mapper->update($account);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
             throw new NotFoundException($e->getMessage());
        }
    }

    public function deleteAccount(int $id): void {
        try {
            $account = $this->mapper->findById($id);
            $this->mapper->delete($account);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
             throw new NotFoundException($e->getMessage());
        }
    }
}
