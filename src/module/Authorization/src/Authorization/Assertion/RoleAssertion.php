<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author       Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license      LGPL-3.0
 * @license      http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link         https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright    Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Authorization\Assertion;

use Authorization\Exception\PermissionNotFoundException;
use Authorization\Service\PermissionServiceAwareTrait;
use Authorization\Service\PermissionServiceInterface;
use Authorization\Service\RoleServiceAwareTrait;
use Language\Manager\LanguageManagerAwareTrait;
use Language\Manager\LanguageManagerInterface;
use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Service\AuthorizationService;

class RoleAssertion implements AssertionInterface
{
    use PermissionServiceAwareTrait, LanguageManagerAwareTrait;

    public function __construct(
        LanguageManagerInterface $languageManager,
        PermissionServiceInterface $permissionService
    ) {
        $this->permissionService = $permissionService;
        $this->languageManager   = $languageManager;
    }

    public function assert(AuthorizationService $authorization, $role = null)
    {
        $assertion = new RequestLanguageAssertion($this->getLanguageManager());
        $checkName = 'authorization.role.' . $role->getName() . '.identity.modify';

        try {
            $this->getPermissionService()->findPermissionByName($checkName);

            return $authorization->isGranted($checkName) && $assertion->assert($authorization);
        } catch (PermissionNotFoundException $e) {
            return $assertion->assert($authorization);
        }
    }
}
