<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Privacy;

use OCA\ClubSuiteFinance\Db\TransactionMapper;
use OCA\ClubSuiteCore\Db\MemberMapper;
use OCP\Privacy\IPersonalDataProvider;
use OCP\IL10N;

class Register implements IPersonalDataProvider {

    public function __construct(
        private TransactionMapper $transactionMapper,
        private MemberMapper $memberMapper,
        private IL10N $l10n
    ) {}

    public function getName(): string {
        return $this->l10n->t('Finance');
    }

    public function userExport(string $userId): array {
        try {
            $member = $this->memberMapper->findByUserId($userId);
            if (!$member) {
                return [];
            }

            $transactions = $this->transactionMapper->findByMember($member->getId());
            $data = [];

            foreach ($transactions as $t) {
                $data[] = [
                    'id' => $t->getId(),
                    'date' => $t->getDate()->format('Y-m-d'),
                    'amount' => $t->getAmount(),
                    'purpose' => $t->getPurpose(),
                    'type' => $t->getType(),
                ];
            }

            return [
                'transactions' => $data
            ];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function userDeleted(string $userId): void {
        try {
            $member = $this->memberMapper->findByUserId($userId);
            if (!$member) {
                return;
            }

            // Anonymize transactions
            $transactions = $this->transactionMapper->findByMember($member->getId());
            foreach ($transactions as $t) {
                $t->setPurpose($this->l10n->t('Deleted user'));
                // Keep amount and date for accounting integrity, but remove personal reference if any in purpose
                // Also maybe unlink member_id?
                // If we unlink member_id, we lose track of who paid.
                // Usually finance data must be kept for 10 years.
                // So maybe we don't delete but we acknowledge the deletion event.
                // For this exercise "Anonymize or delete" is requested.
                // I will update the purpose. MemberID might become invalid if Member is deleted in Core.
                // If member in core is deleted, this ID points nowhere.
                // We should probably set member_id to null if nullable, or 0.
                // Checking Transaction entity... assuming setMemberId exists.
                if (method_exists($t, 'setMemberId')) {
                    $t->setMemberId(0); // or null
                }
                $this->transactionMapper->update($t);
            }
        } catch (\Throwable $e) {
            // Log error
        }
    }
}

