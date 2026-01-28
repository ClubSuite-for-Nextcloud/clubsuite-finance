<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Controller;

use OCA\ClubSuiteFinance\Exception\NotFoundException;
use OCA\ClubSuiteFinance\Service\TransactionService;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\IGroupManager;
use OCP\IRequest;
use OCP\IUserSession;

class TransactionApiController extends BaseFinanceController {

    private TransactionService $service;

    public function __construct(
        IRequest $request,
        IUserSession $userSession,
        IGroupManager $groupManager,
        TransactionService $service
    ) {
        parent::__construct('clubsuite-finance', $request, $userSession, $groupManager);
        $this->service = $service;
    }

    /**
     * @NoAdminRequired
     */
    public function index(): DataResponse {
        return new DataResponse($this->service->listTransactions());
    }

    /**
     * @NoAdminRequired
     */
    public function show(int $id): DataResponse {
        return new DataResponse(['status' => 'not_implemented']);
    }

    /**
     * @NoAdminRequired
     */
    public function create(int $accountId, int $amount, string $date, ?int $categoryId = null, ?int $memberId = null, ?string $purpose = null): DataResponse {
        return new DataResponse($this->service->createTransaction($accountId, $amount, $date, $categoryId, $memberId, $purpose));
    }

    /**
     * @NoAdminRequired
     */
    public function update(int $id, int $accountId, int $amount, string $date, ?int $categoryId = null, ?int $memberId = null, ?string $purpose = null): DataResponse {
        try {
            return new DataResponse($this->service->updateTransaction($id, $accountId, $amount, $date, $categoryId, $memberId, $purpose));
        } catch (NotFoundException $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_NOT_FOUND);
        }
    }

    /**
     * @NoAdminRequired
     */
    public function destroy(int $id): DataResponse {
        try {
            $this->service->deleteTransaction($id);
            return new DataResponse([], Http::STATUS_OK);
        } catch (NotFoundException $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_NOT_FOUND);
        }
    }
}
