<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Uuid\Manager;

use Authorization\Service\AuthorizationAssertionTrait;
use ClassResolver\ClassResolverAwareTrait;
use ClassResolver\ClassResolverInterface;
use Common\Traits\FlushableTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Uuid\Entity\UuidHolder;
use Uuid\Entity\UuidInterface;
use Uuid\Exception\InvalidArgumentException;
use Uuid\Exception\NotFoundException;
use Uuid\Exception;
use Uuid\Options\ModuleOptions;
use Zend\EventManager\EventManagerAwareTrait;
use ZfcRbac\Service\AuthorizationService;

class UuidManager implements UuidManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;
    use EventManagerAwareTrait, FlushableTrait;
    use AuthorizationAssertionTrait;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @param AuthorizationService   $authorizationService
     * @param ClassResolverInterface $classResolver
     * @param ModuleOptions          $moduleOptions
     * @param ObjectManager          $objectManager
     */
    public function __construct(
        AuthorizationService $authorizationService,
        ClassResolverInterface $classResolver,
        ModuleOptions $moduleOptions,
        ObjectManager $objectManager
    ) {
        $this->authorizationService = $authorizationService;
        $this->classResolver        = $classResolver;
        $this->objectManager        = $objectManager;
        $this->moduleOptions        = $moduleOptions;
    }

    /**
     * @return ModuleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    public function findByTrashed($trashed)
    {
        $className = $this->getClassResolver()->resolveClassName('Uuid\Entity\UuidInterface');
        $criteria  = [
            'trashed' => $trashed
        ];
        $entities  = $this->getObjectManager()->getRepository($className)->findBy($criteria);

        return new ArrayCollection($entities);
    }

    public function injectUuid(UuidHolder $entity, UuidInterface $uuid = null)
    {
        if ($uuid === null) {
            $uuid = $this->createUuid();
        }

        return $entity->setUuid($uuid);
    }

    public function getUuid($key)
    {
        $className = $this->getClassResolver()->resolveClassName('Uuid\Entity\UuidInterface');
        $entity    = $this->getObjectManager()->find($className, $key);

        if (!is_object($entity)) {
            throw new NotFoundException(sprintf('Could not find %s', $key));
        }

        return $entity;
    }

    public function findUuidByName($string)
    {
        if (!is_string($string)) {
            throw new InvalidArgumentException(sprintf('Expected string but got %s', gettype($string)));
        }

        $className = $this->getClassResolver()->resolveClassName('Uuid\Entity\UuidInterface');
        $criteria  = [
            'uuid' => (string)$string
        ];
        $entity    = $this->getObjectManager()->getRepository($className)->findOneBy($criteria);

        if (!$entity) {
            throw new NotFoundException(sprintf('Could not find %s', $string));
        }

        return $entity;
    }

    public function trashUuid($id)
    {
        $uuid       = $this->getUuid($id);
        $permission = $this->getModuleOptions()->getPermission($uuid->getHolderName(), 'trash');
        $this->assertGranted($permission, $uuid);

        $uuid->setTrashed(true);
        $this->getObjectManager()->persist($uuid);

        $this->getEventManager()->trigger(
            'trash',
            $this,
            [
                'object' => $uuid
            ]
        );
    }

    public function restoreUuid($id)
    {
        $uuid       = $this->getUuid($id);
        $permission = $this->getModuleOptions()->getPermission($uuid->getHolderName(), 'restore');
        $this->assertGranted($permission, $uuid);

        $uuid->setTrashed(false);
        $this->getObjectManager()->persist($uuid);

        $this->getEventManager()->trigger(
            'restore',
            $this,
            [
                'object' => $uuid
            ]
        );
    }

    public function purgeUuid($id)
    {
        $uuid       = $this->getUuid($id);
        $permission = $this->getModuleOptions()->getPermission($uuid, 'purge');
        $this->assertGranted($permission, $uuid);

        $this->getObjectManager()->remove($uuid);

        $this->getEventManager()->trigger(
            'purge',
            $this,
            [
                'object' => $uuid
            ]
        );
    }

    public function createUuid()
    {
        $entity = $this->getClassResolver()->resolve('Uuid\Entity\UuidInterface');

        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush($entity);

        return $entity;
    }

    protected function ambiguousToUuid($idOrObject)
    {
        $uuid = null;

        if (is_int($idOrObject)) {
            $uuid = $this->getUuid($idOrObject);
        } elseif ($idOrObject instanceof UuidHolder) {
            $uuid = $idOrObject->getUuidEntity();
        } elseif ($idOrObject instanceof UuidInterface) {
            $uuid = $idOrObject;
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected int, UuidHolder or UuidInterface but got "%s"',
                (is_object($idOrObject) ? get_class($idOrObject) : gettype($idOrObject))
            ));
        }

        return $uuid;
    }
}
