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
     * @param ObjectManager          $objectManager
     * @param ClassResolverInterface $classResolver
     */
    public function __construct(ClassResolverInterface $classResolver, ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->classResolver = $classResolver;
    }

    /**
     * @var string
     */
    protected $interface = 'Authorization\Entity\PermissionInterface';

    public function getPermission($id)
    {
        $className  = $this->getClassResolver()->resolveClassName($this->interface);
        $permission = $this->getObjectManager()->find($className, $id);

        if (!is_object($permission)) {
            throw new PermissionNotFoundException(sprintf('Permission %d not found', $id));
        }

        return $permission;
    }

    public function findPermissionByName($name)
    {
        $className  = $this->getClassResolver()->resolveClassName($this->interface);
        $repository = $this->getObjectManager()->getRepository($className);
        $permission = $repository->findOneBy(['name' => $name]);

        if (!is_object($permission)) {
            throw new PermissionNotFoundException(sprintf('Permission `%s` not found', $name));
        }

        return $permission;
    }

    public function findAllPermissions()
    {
        $className  = $this->getClassResolver()->resolveClassName($this->interface);
        $repository = $this->getObjectManager()->getRepository($className);

        return $repository->findAll();
    }
}
