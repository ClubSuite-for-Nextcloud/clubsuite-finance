<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Service;

use OCA\ClubSuiteFinance\Db\Transaction;
use OCA\ClubSuiteFinance\Db\TransactionMapper;
use OCA\ClubSuiteFinance\Exception\NotFoundException;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\ILogger;

class TransactionService {

    public function __construct(
        private TransactionMapper $mapper,
        private ILogger $logger
    ) {}

    public function listTransactions(): array {
        return $this->mapper->findAll();
    }

    public function listByAccount(int $accountId): array {
        return $this->mapper->findByAccount($accountId);
    }

    public function listByMember(int $memberId): array {
        return $this->mapper->findByMember($memberId);
    }

    public function createTransaction(int $accountId, float $amount, string $date, ?int $categoryId = null, ?int $memberId = null, string $purpose = ''): Transaction {
        $transaction = new Transaction();
        $transaction->setAccountId($accountId);
        $transaction->setAmount($amount); // Entity handles type (usually int/float)
        $transaction->setDate(new \DateTime($date));
        $transaction->setPurpose($purpose);
        if ($categoryId) $transaction->setCategoryId($categoryId);
        if ($memberId) $transaction->setMemberId($memberId);

        $result = $this->mapper->insert($transaction);
        $this->logger->info(\sprintf('[clubsuite-finance] Posted transaction: %s (Amount: %s)', $purpose, $amount));
        return $result;
    }

    public function updateTransaction(int $id, int $accountId, float $amount, string $date, ?int $categoryId = null, ?int $memberId = null, string $purpose = ''): Transaction {
        try {
            $transaction = $this->mapper->findById($id);
            $transaction->setAccountId($accountId);
            $transaction->setCategoryId($categoryId);
            $transaction->setMemberId($memberId);
            $transaction->setAmount($amount);
            $transaction->setDate(new \DateTime($date));
            $transaction->setPurpose($purpose);
            
            $result = $this->mapper->update($transaction);
            $this->logger->info(\sprintf('[clubsuite-finance] Updated transaction: %d', $id));
            return $result;
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
             $this->logger->error('[clubsuite-finance] Error updating transaction ' . $id . ': ' . $e->getMessage());
             throw new NotFoundException($e->getMessage());
        }
    }

    public function deleteTransaction(int $id): void {
        try {
            $transaction = $this->mapper->findById($id);
            $this->mapper->delete($transaction);
            $this->logger->info(\sprintf('[clubsuite-finance] Deleted transaction: %d', $id));
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
             throw new NotFoundException($e->getMessage());
        }
    }
}
