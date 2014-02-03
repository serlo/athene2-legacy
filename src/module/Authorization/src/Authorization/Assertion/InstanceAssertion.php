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
namespace Authorization\Assertion;

use Authorization\Exception\InvalidArgumentException;
use Authorization\Exception\PermissionNotFoundException;
use Authorization\Result\AuthorizationResult;
use Authorization\Service\PermissionServiceInterface;
use Instance\Entity\InstanceInterface;
use Instance\Entity\InstanceProviderInterface;
use Instance\Manager\InstanceManagerInterface;
use Rbac\Traversal\Strategy\TraversalStrategyInterface;

class InstanceAssertion implements AssertionInterface
{
    /**
     * @var PermissionServiceInterface
     */
    protected $permissionService;

    /**
     * @var InstanceManagerInterface
     */
    protected $instanceManager;

    /**
     * @var TraversalStrategyInterface
     */
    protected $traversalStrategy;

    /**
     * @param InstanceManagerInterface   $instanceManager
     * @param PermissionServiceInterface $permissionService
     * @param TraversalStrategyInterface $traversalStrategy
     */
    public function __construct(
        InstanceManagerInterface $instanceManager,
        PermissionServiceInterface $permissionService,
        TraversalStrategyInterface $traversalStrategy
    ) {
        $this->permissionService = $permissionService;
        $this->instanceManager   = $instanceManager;
        $this->traversalStrategy = $traversalStrategy;
    }

    /**
     * Check if this assertion is true
     *
     * @param  AuthorizationResult $authorization
     * @param  mixed               $context
     * @throws InvalidArgumentException
     * @return bool
     */
    public function assert(AuthorizationResult $authorization, $context = null)
    {
        if ($context === null) {
            $instance = $this->instanceManager->getInstanceFromRequest();
        } elseif ($context instanceof InstanceProviderInterface) {
            $instance = $context->getInstance();
        } elseif ($context instanceof InstanceInterface) {
            $instance = $context;
        } else {
            throw new InvalidArgumentException;
        }

        $permission = $authorization->getPermission();
        $roles      = $this->flattenRoles($authorization->getRoles());

        try {
            $permission = $this->permissionService->findParametrizedPermission(
                $permission,
                'instance',
                $instance
            );
        } catch (PermissionNotFoundException $e) {
            try {
                // maybe the asked permission is granted globally?
                $permission = $this->permissionService->findParametrizedPermission(
                    $permission,
                    'instance',
                    null
                );
            } catch (PermissionNotFoundException $e) {
                // couldn't find permission, so this isn't going to be authorized
                return false;
            }
        }

        foreach ($roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Flatten an array of role with role names
     * This method iterates through the list of roles, and convert any RoleInterface to a string. For any
     * role, it also extracts all the children
     *
     * @param  array|RoleInterface[] $roles
     * @return RoleInterface[]
     */
    public function flattenRoles(array $roles)
    {
        $roleNames = [];
        $iterator  = $this->traversalStrategy->getRolesIterator($roles);

        foreach ($iterator as $role) {
            $roleNames[] = $role;
        }

        return array_unique($roleNames);
    }
}
