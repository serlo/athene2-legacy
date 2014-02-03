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

use Authorization\Exception\PermissionNotFoundException;
use ClassResolver\ClassResolverAwareTrait;
use ClassResolver\ClassResolverInterface;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Persistence\ObjectManager;

class PermissionService implements PermissionServiceInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;

    /**
     * @var string
     */
    protected $permissionInterface = 'Authorization\Entity\PermissionInterface';
    /**
     * @var string
     */
    protected $instancePermissionInterface = 'Authorization\Entity\ParametrizedPermissionInterface';

    /**
     * @param ObjectManager          $objectManager
     * @param ClassResolverInterface $classResolver
     */
    public function __construct(ClassResolverInterface $classResolver, ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->classResolver = $classResolver;
    }

    public function getParametrizedPermission($id)
    {
        $className  = $this->getClassResolver()->resolveClassName($this->instancePermissionInterface);
        $permission = $this->getObjectManager()->find($className, $id);

        if (!is_object($permission)) {
            throw new PermissionNotFoundException(sprintf('Permission %d not found', $id));
        }

        return $permission;
    }

    public function getPermission($id)
    {
        $className  = $this->getClassResolver()->resolveClassName($this->permissionInterface);
        $permission = $this->getObjectManager()->find($className, $id);

        if (!is_object($permission)) {
            throw new PermissionNotFoundException(sprintf('Permission %d not found', $id));
        }

        return $permission;
    }

    public function findParametrizedPermissionByNameAndParameter($name, $parameterKey, $parameterValue)
    {
        $className  = $this->getClassResolver()->resolveClassName($this->instancePermissionInterface);
        $repository = $this->getObjectManager()->getRepository($className);
        $permission = $repository->findOneBy(
            [
                'permission'  => $this->findPermissionByName($name)->getId(),
                $parameterKey => $parameterValue
            ]
        );

        if (!is_object($permission)) {
            throw new PermissionNotFoundException(sprintf(
                'Permission %s with parameter %s = %s not found',
                $name,
                $parameterKey,
                $parameterValue
            ));
        }

        return $permission;
    }

    public function findPermissionByName($name)
    {
        $className  = $this->getClassResolver()->resolveClassName($this->permissionInterface);
        $repository = $this->getObjectManager()->getRepository($className);
        $permission = $repository->findOneBy(['name' => $name]);

        if (!is_object($permission)) {
            throw new PermissionNotFoundException(sprintf('Permission `%s` not found', $name));
        }

        return $permission;
    }

    public function findAllPermissions()
    {
        $className  = $this->getClassResolver()->resolveClassName($this->permissionInterface);
        $repository = $this->getObjectManager()->getRepository($className);

        return $repository->findAll();
    }
}
