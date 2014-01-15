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
namespace Authorization\Service;

use Authorization\Exception\RoleNotFoundException;
use ClassResolver\ClassResolverAwareTrait;
use Common\ObjectManager\Flushable;
use Common\Traits\ObjectManagerAwareTrait;
use User\Manager\UserManagerAwareTrait;

class RoleService implements RoleServiceInterface, Flushable
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait, UserManagerAwareTrait, PermissionServiceAwareTrait,
        AuthorizationAssertionTrait;

    protected $interface = 'Authorization\Entity\RoleInterface';

    public function findAllRoles()
    {
        $className = $this->getClassResolver()->resolveClassName($this->interface);

        return $this->getObjectManager()->getRepository($className)->findAll();
    }

    public function getRole($id)
    {
        $className = $this->getClassResolver()->resolveClassName($this->interface);
        $role      = $this->getObjectManager()->find($className, $id);

        if (!is_object($role)) {
            throw new RoleNotFoundException(sprintf('Role `%d` not found', $id));
        }

        return $role;
    }

    public function grantRolePermission($roleId, $permissionId)
    {
        $this->assertGranted('authorization.permission.add');
        $permission = $this->getPermissionService()->getPermission($permissionId);
        $role       = $this->getRole($roleId);
        $role->addPermission($permission);
    }

    public function removeRolePermission($roleId, $permissionId)
    {
        $this->assertGranted('authorization.permission.remove');
        $permission = $this->getPermissionService()->getPermission($permissionId);
        $role       = $this->getRole($roleId);
        $role->removePermission($permission);
    }

    public function grantIdentityRole($roleId, $userId)
    {
        $role = $this->getRole($roleId);
        $this->assertGranted('authorization.role.identity.modify', $role);
        $user = $this->getUserManager()->getUser($userId);
        if (!$user->hasRole($role)) {
            $role->addUser($user);
        }
    }

    public function removeIdentityRole($roleId, $userId)
    {
        $role = $this->getRole($roleId);
        $this->assertGranted('authorization.role.identity.modify', $role);
        $user = $this->getUserManager()->getUser($userId);
        if ($user->hasRole($role)) {
            $role->removeUser($user);
        }
    }

    public function flush()
    {
        $this->getObjectManager()->flush();
    }
}
