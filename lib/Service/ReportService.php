<?php
namespace OCA\ClubSuiteFinance\Service;

use OCA\ClubSuiteFinance\Db\TransactionMapper;

class ReportService {
    private TransactionMapper $mapper;

    public function __construct(TransactionMapper $mapper) { $this->mapper = $mapper; }

    /**
     * Generate a simple annual report (placeholder).
     * Returns aggregated totals by type for the given year.
     */
    public function generateAnnualReport(int $year): array {
        $all = $this->mapper->findAll();
        $income = 0.0; $expense = 0.0;
        foreach ($all as $t) {
            $y = (int)$t->getDate()->format('Y');
            if ($y !== $year) continue;
            if ($t->getType() === 'income') $income += $t->getAmount();
            else $expense += $t->getAmount();
        }
        return ['year' => $year, 'income' => $income, 'expense' => $expense, 'balance' => $income - $expense];
    }
}
