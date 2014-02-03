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
namespace Authorization\Controller;

use Authorization\Form\PermissionForm;
use Authorization\Form\RoleForm;
use Authorization\Form\UserForm;
use Authorization\Service\PermissionServiceAwareTrait;
use Authorization\Service\PermissionServiceInterface;
use Authorization\Service\RoleServiceAwareTrait;
use Authorization\Service\RoleServiceInterface;
use Instance\Entity\InstanceAwareTrait;
use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use User\Exception\UserNotFoundException;
use User\Manager\UserManagerAwareTrait;
use User\Manager\UserManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RoleController extends AbstractActionController
{
    use RoleServiceAwareTrait, UserManagerAwareTrait, PermissionServiceAwareTrait, InstanceManagerAwareTrait;

    /**
     * @var RoleForm
     */
    protected $roleForm;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        PermissionServiceInterface $permissionService,
        RoleServiceInterface $roleService,
        UserManagerInterface $userManager,
        RoleForm $roleForm
    ) {
        $this->roleService       = $roleService;
        $this->userManager       = $userManager;
        $this->permissionService = $permissionService;
        $this->instanceManager   = $instanceManager;
        $this->roleForm          = $roleForm;
    }

    public function addPermissionAction()
    {
        $this->assertGranted('authorization.role.grant.permission');

        $permissions = $this->getPermissionService()->findAllPermissions();
        $role        = $this->getRoleService()->getRole($this->params('role'));
        $permissions = array_diff($permissions, $role->getPermissions()->toArray());
        $instances   = $this->getInstanceManager()->findAllInstances();
        $form        = new PermissionForm($permissions, $instances);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->getRoleService()->grantRolePermission($this->params('role'), $data['permission']);
                $this->getRoleService()->flush();

                return $this->redirect()->toUrl($this->referer()->fromStorage());
            }
        } else {
            $this->referer()->store();
        }

        $view = new ViewModel([
            'form' => $form,
        ]);

        $view->setTemplate('authorization/role/permission/add');

        return $view;
    }

    public function addUserAction()
    {
        $role = $this->getRoleService()->getRole($this->params('role'));
        $this->assertGranted('authorization.identity.grant.role', $role);

        $form  = new UserForm();
        $error = false;
        $user  = null;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                try {
                    $user = $this->getUserManager()->findUserByUsername($data['user']);
                    $this->getRoleService()->grantIdentityRole($this->params('role'), $user->getId());
                    $this->getRoleService()->flush();

                    return $this->redirect()->toUrl($this->referer()->fromStorage());
                } catch (UserNotFoundException $e) {
                    $error = true;
                    $user  = $data['user'];
                }
            }
        } else {
            $this->referer()->store();
        }

        $view = new ViewModel([
            'error' => $error,
            'form'  => $form,
            'user'  => $user
        ]);

        $view->setTemplate('authorization/role/user/add');

        return $view;
    }

    public function createRoleAction()
    {
        $this->assertGranted('authorization.role.create');

        $view = new ViewModel(['form' => $this->roleForm]);
        $view->setTemplate('authorization/role/create');

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->roleForm->setData($data);
            if ($this->roleForm->isValid()) {
                $this->roleService->createRole($this->roleForm);
                $this->roleService->flush();
                $this->flashmessenger()->addSuccessMessage('Role created.');

                return $this->redirect()->toUrl($this->referer()->fromStorage());
            }
        } else {
            $this->referer()->store();
        }

        return $view;
    }

    public function removePermissionAction()
    {
        $this->assertGranted('authorization.role.revoke.permission');

        $this->getRoleService()->removeRolePermission($this->params('role'), $this->params('permission'));
        $this->getRoleService()->flush();
        $this->redirect()->toUrl($this->referer()->toUrl());

        return null;
    }

    public function removeUserAction()
    {
        $role = $this->getRoleService()->getRole($this->params('role'));
        $this->assertGranted('authorization.identity.revoke.role', $role);

        $form  = new UserForm();
        $error = false;
        $user  = null;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                try {
                    $user = $this->getUserManager()->findUserByUsername($data['user']);
                    $this->getRoleService()->removeIdentityRole($this->params('role'), $user->getId());
                    $this->getRoleService()->flush();
                    $this->redirect()->toUrl($this->referer()->fromStorage());

                    return null;
                } catch (UserNotFoundException $e) {
                    $error = true;
                    $user  = $data['user'];
                }
            }
        } else {
            $this->referer()->store();
        }

        $view = new ViewModel([
            'error' => $error,
            'form'  => $form,
            'user'  => $user
        ]);

        $view->setTemplate('authorization/role/user/remove');

        return $view;
    }

    public function rolesAction()
    {
        $view = new ViewModel(array(
            'roles' => $this->getRoleService()->findAllRoles()
        ));
        $view->setTemplate('authorization/role/roles');

        return $view;
    }

    public function showAction()
    {
        $role = $this->params('role');
        $role = $this->getRoleService()->getRole($role);

        $view = new ViewModel(array(
            'role'  => $role,
            'users' => $role->getUsers()
        ));

        return $view;
    }
}
