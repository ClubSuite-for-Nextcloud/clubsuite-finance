<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Controller;

use OCP\AppFramework\ApiController;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\IGroupManager;
use OCP\IL10N;
use OCP\IRequest;
use OCP\IUserSession;

abstract class BaseFinanceController extends ApiController {

    protected IUserSession $userSession;
    protected IGroupManager $groupManager;

    private IL10N $l10n;

    public function __construct(
        string $appName,
        IRequest $request,
        IUserSession $userSession,
        IGroupManager $groupManager,
        IL10N $l10n
    ) {
        parent::__construct($appName, $request);
        $this->userSession = $userSession;
        $this->groupManager = $groupManager;
        $this->l10n = $l10n;
    }

    /**
     * Check if user is allowed to access finance
     * @throws \Exception
     */
    protected function checkPermissions(): void {
        if (!$this->isAllowed()) {
            throw new \Exception($this->l10n->t('Permission denied'), 403);
        }
    }

    /**
     * Check if user is logged in and has permission
     */
    protected function isAllowed(): bool {
        if (!$this->userSession->isLoggedIn()) {
            return false;
        }
        $user = $this->userSession->getUser();
        if (!$user) {
            return false;
        }

        // Admin check
        if ($this->groupManager->isAdmin($user->getUID())) {
            return true;
        }

        // 'Vorstand' group check
        $group = $this->groupManager->get('Vorstand');
        if ($group && $group->inGroup($user)) {
             return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function beforeController($controller, $methodName): void {
        if (!$this->isAllowed()) {
            throw new \Exception('Access denied', 403); 
            // In real app, cleaner to return JSON Error Response or throw specific ForbiddenException
        }
        parent::beforeController($controller, $methodName);
    }
}
