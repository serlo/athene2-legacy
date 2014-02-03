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
use ClassResolver\ClassResolverInterface;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Persistence\ObjectManager;
use User\Entity\Role;
use User\Manager\UserManagerAwareTrait;
use User\Manager\UserManagerInterface;
use Zend\Form\FormInterface;
use ZfcRbac\Service\AuthorizationService;

class RoleService implements RoleServiceInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait, UserManagerAwareTrait, PermissionServiceAwareTrait,
        AuthorizationAssertionTrait;

    protected $interface = 'Authorization\Entity\RoleInterface';

    public function __construct(
        AuthorizationService $authorizationService,
        ClassResolverInterface $classResolver,
        ObjectManager $objectManager,
        PermissionServiceInterface $permissionService,
        UserManagerInterface $userManager
    ) {
        $this->authorizationService = $authorizationService;
        $this->classResolver        = $classResolver;
        $this->objectManager        = $objectManager;
        $this->userManager          = $userManager;
        $this->permissionService    = $permissionService;
    }

    public function findAllRoles()
    {
        $className = $this->getClassResolver()->resolveClassName($this->interface);

        return $this->getObjectManager()->getRepository($className)->findAll();
    }

    public function findRoleByName($name)
    {
        $className = $this->getClassResolver()->resolveClassName($this->interface);

        return $this->getObjectManager()->getRepository($className)->findOneBy(
            [
                'name' => $name
            ]
        );
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

    public function createRole(FormInterface $form)
    {
        $this->assertGranted('authorization.role.create');
        
        $data = $form->getData();

        $form->bind(new Role());
        $form->setData($data);

        if ($form->isValid()) {
            $this->objectManager->persist($form->getObject());
        }

        return $form->getObject();
    }

    public function grantRolePermission($roleId, $permissionId)
    {
        $this->assertGranted('authorization.role.grant.permission');
        $permission = $this->getPermissionService()->getParametrizedPermission($permissionId);
        $role       = $this->getRole($roleId);
        $role->addPermission($permission);
    }

    public function removeRolePermission($roleId, $permissionId)
    {
        $this->assertGranted('authorization.role.revoke.permission');
        $permission = $this->getPermissionService()->getParametrizedPermission($permissionId);
        $role       = $this->getRole($roleId);
        $role->removePermission($permission);
    }

    public function grantIdentityRole($roleId, $userId)
    {
        $role = $this->getRole($roleId);
        $this->assertGranted('authorization.identity.grant.role', $role);
        $user = $this->getUserManager()->getUser($userId);
        if (!$user->hasRole($role)) {
            $role->addUser($user);
        }
    }

    public function removeIdentityRole($roleId, $userId)
    {
        $role = $this->getRole($roleId);
        $this->assertGranted('authorization.identity.revoke.role', $role);
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
