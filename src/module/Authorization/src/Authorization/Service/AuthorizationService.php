<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Authorization\Service;

use Authorization\Result\AuthorizationResult;
use Rbac\Rbac;
use ZfcRbac\Assertion\AssertionPluginManager;
use ZfcRbac\Exception;
use ZfcRbac\Service\RoleService;

class AuthorizationService extends \ZfcRbac\Service\AuthorizationService
{
    /**
     * @var AuthorizationResult
     */
    protected $authorizationResult;

    public function __construct(Rbac $rbac, RoleService $roleService, AssertionPluginManager $assertionPluginManager)
    {
        parent::__construct($rbac, $roleService, $assertionPluginManager);
        $this->authorizationResult = new AuthorizationResult();
    }

    /**
     * @return AuthorizationResult
     */
    public function getAuthorizationResult()
    {
        return $this->authorizationResult;
    }

    /**
     * @param \Rbac\Permission\PermissionInterface|string $permission
     * @param null                                        $context
     * @return bool
     */
    public function isGranted($permission, $context = null)
    {
        $roles = $this->roleService->getIdentityRoles();

        $this->updateResult($permission, $roles);

        if (empty($roles)) {
            return false;
        }

        if (!$this->rbac->isGranted($roles, $permission)) {
            return false;
        }

        if ($this->hasAssertion($permission)) {
            return $this->assert($this->assertions[$permission], $context);
        }

        return true;
    }

    /**
     * @param string $permission
     * @param array  $roles
     * @return void
     */
    protected function updateResult($permission, $roles)
    {
        $this->authorizationResult->setPermission($permission);
        $this->authorizationResult->setIdentity($this->getIdentity());
        $this->authorizationResult->setRoles($roles);
    }
}

 