<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Controller;

use Exception;
use OCA\ClubSuiteFinance\Service\AccountService;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\IGroupManager;
use OCP\IL10N;
use OCP\ILogger;
use OCP\IRequest;
use OCP\IUserSession;

class AccountApiController extends BaseFinanceController {

    public function __construct(
        IRequest $request,
        IUserSession $userSession,
        IGroupManager $groupManager,
        IL10N $l10n,
        private AccountService $service,
        private ILogger $logger
    ) {
        parent::__construct('clubsuite-finance', $request, $userSession, $groupManager, $l10n);
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function index(): DataResponse {
        try {
            $this->checkPermissions();
            return new DataResponse($this->service->listAccounts());
        } catch (Exception $e) {
            $this->logException($e);
            return $this->errorResponse($e);
        }
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function show(int $id): DataResponse {
        try {
            $this->checkPermissions();
            return new DataResponse($this->service->getAccount($id));
        } catch (Exception $e) {
            $this->logException($e);
            return $this->errorResponse($e);
        }
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function create(string $name, string $type): DataResponse {
        try {
            $this->checkPermissions();
            $account = $this->service->createAccount($name, $type);
            $this->logger->info(\sprintf('[clubsuite-finance] Created account: %s (%s)', $name, $type));
            return new DataResponse($account);
        } catch (Exception $e) {
            $this->logException($e);
            return $this->errorResponse($e);
        }
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function update(int $id, string $name, string $type): DataResponse {
        try {
            $this->checkPermissions();
            $account = $this->service->updateAccount($id, $name, $type);
            $this->logger->info(\sprintf('[clubsuite-finance] Updated account: %d', $id));
            return new DataResponse($account);
        } catch (Exception $e) {
            $this->logException($e);
            return $this->errorResponse($e);
        }
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function destroy(int $id): DataResponse {
        try {
            $this->checkPermissions();
            $this->service->deleteAccount($id);
            $this->logger->info(\sprintf('[clubsuite-finance] Deleted account: %d', $id));
            return new DataResponse(['status' => 'success']);
        } catch (Exception $e) {
            $this->logException($e);
            return $this->errorResponse($e);
        }
    }

    private function logException(Exception $e): void {
        $level = $e->getCode() === 403 ? 'warning' : 'error';
        $this->logger->$level(\sprintf('[clubsuite-finance] Error in AccountApi: %s', $e->getMessage()));
    }

    private function errorResponse(Exception $e): DataResponse {
        $code = $e->getCode();
        $httpCode = ($code >= 400 && $code < 600) ? $code : 500;
        return new DataResponse(['error' => $e->getMessage()], $httpCode);
    }
}
