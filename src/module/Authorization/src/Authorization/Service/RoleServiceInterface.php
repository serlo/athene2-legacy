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

use Common\ObjectManager\Flushable;
use Rbac\Role\RoleInterface;
use Zend\Form\FormInterface;

interface RoleServiceInterface extends Flushable
{
    /**
     * @param FormInterface $form
     * @return RoleInterface
     */
    public function createRole(FormInterface $form);

    /**
     * @return RoleInterface[]
     */
    public function findAllRoles();

    /**
     * @param $name
     * @return RoleInterface
     */
    public function findRoleByName($name);

    /**
     * @param int $id
     * @return RoleInterface
     */
    public function getRole($id);

    /**
     * @param int $roleId
     * @param int $userId
     * @return void
     */
    public function grantIdentityRole($roleId, $userId);

    /**
     * @param int $roleId
     * @param int $permissionId
     * @return void
     */
    public function grantRolePermission($roleId, $permissionId);

    /**
     * @param int $roleId
     * @param int $userId
     * @return void
     */
    public function removeIdentityRole($roleId, $userId);

    /**
     * @param int $roleId
     * @param int $permissionId
     * @return void
     */
    public function removeRolePermission($roleId, $permissionId);
} 