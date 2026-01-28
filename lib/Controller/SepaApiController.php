<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Controller;

use OCA\ClubSuiteFinance\Service\SepaExportService;
use OCA\ClubSuiteFinance\Service\TransactionService;
use OCP\AppFramework\Http\DataDownloadResponse;
use OCP\IGroupManager;
use OCP\IRequest;
use OCP\IUserSession;

class SepaApiController extends BaseFinanceController {

    private SepaExportService $sepaService;
    private TransactionService $transactionService;

    public function __construct(
        IRequest $request,
        IUserSession $userSession,
        IGroupManager $groupManager,
        SepaExportService $sepaService,
        TransactionService $transactionService
    ) {
        parent::__construct('clubsuite-finance', $request, $userSession, $groupManager);
        $this->sepaService = $sepaService;
        $this->transactionService = $transactionService;
    }

    /**
     * @NoAdminRequired
     */
    public function export(): DataDownloadResponse {
        // Fetch all transactions (or filter by request params if implemented)
        $transactions = $this->transactionService->listTransactions();
        
        $xmlContent = $this->sepaService->generateSepaXml($transactions);
        
        return new DataDownloadResponse($xmlContent, 'sepa-export-' . date('Ymd') . '.xml', 'application/xml');
    }
}
