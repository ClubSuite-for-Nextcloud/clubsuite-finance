<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Controller;

use Exception;
use OCA\ClubSuiteFinance\Service\CategoryService;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\IGroupManager;
use OCP\IL10N;
use OCP\ILogger;
use OCP\IRequest;
use OCP\IUserSession;

class CategoryApiController extends BaseFinanceController {

    public function __construct(
        IRequest $request,
        IUserSession $userSession,
        IGroupManager $groupManager,
        IL10N $l10n,
        private CategoryService $service,
        private ILogger $logger
    ) {
        parent::__construct('clubsuite-finance', $request, $userSession, $groupManager, $l10n);
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function index(): DataResponse {
        try {
            $this->checkPermissions();
            return new DataResponse($this->service->listCategories());
        } catch (Exception $e) {
            $this->logger->error('[clubsuite-finance] Error listing categories: ' . $e->getMessage());
            return new DataResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function create(string $name): DataResponse {
        try {
            $this->checkPermissions();
            $category = $this->service->createCategory($name);
            $this->logger->info(\sprintf('[clubsuite-finance] Created category: %s', $name));
            return new DataResponse($category);
        } catch (Exception $e) {
            $this->logger->error('[clubsuite-finance] Error creating category: ' . $e->getMessage());
            return new DataResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function update(int $id, string $name): DataResponse {
        try {
            $this->checkPermissions();
            $category = $this->service->updateCategory($id, $name);
            $this->logger->info(\sprintf('[clubsuite-finance] Updated category: %d', $id));
            return new DataResponse($category);
        } catch (Exception $e) {
            $this->logger->error('[clubsuite-finance] Error updating category: ' . $e->getMessage());
            return new DataResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[NoAdminRequired]
    #[NoCSRFRequired]
    public function destroy(int $id): DataResponse {
        try {
            $this->checkPermissions();
            $this->service->deleteCategory($id);
            $this->logger->info(\sprintf('[clubsuite-finance] Deleted category: %d', $id));
            return new DataResponse(['status' => 'success']);
        } catch (Exception $e) {
            $this->logger->error('[clubsuite-finance] Error deleting category: ' . $e->getMessage());
            return new DataResponse(['error' => $e->getMessage()], 400);
        }
    }
}
