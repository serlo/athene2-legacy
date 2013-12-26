<?php
/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Uuid\Manager;

use Uuid\Entity\UuidHolder;
use Uuid\Entity\UuidInterface;
use Uuid\Exception\InvalidArgumentException;
use Uuid\Exception\NotFoundException;
use Uuid\Exception;
use Doctrine\Common\Collections\ArrayCollection;

class UuidManager implements UuidManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait;
    use\Common\Traits\ConfigAwareTrait,\Zend\EventManager\EventManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'resolver' => array()
        );
    }

    public function findByTrashed($trashed)
    {
        $className = $this->getClassResolver()->resolveClassName('Uuid\Entity\UuidInterface');
        $entities = $this->getObjectManager()
            ->getRepository($className)
            ->findBy(array(
            'trashed' => $trashed
        ));
        return new ArrayCollection($entities);
    }

    public function injectUuid(UuidHolder $entity, UuidInterface $uuid = NULL)
    {
        if (! $uuid) {
            $uuid = $this->createUuid();
        }
        
        return $entity->setUuid($uuid);
    }

    public function getUuid($key)
    {
        $entity = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Uuid\Entity\UuidInterface'), $key);
        
        if (! is_object($entity)) {
            throw new NotFoundException(sprintf('Could not find %s', $key));
        }
        
        return $entity;
    }

    public function findUuidByName($string)
    {
        if (! is_string($string)) {
            throw new InvalidArgumentException(sprintf('Expected string but got %s', gettype($string)));
        }
        
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Uuid\Entity\UuidInterface'))
            ->findOneBy(array(
            'uuid' => (string) $string
        ));
        
        if (! $entity) {
            throw new NotFoundException(sprintf('Could not find %s', $string));
        }
        
        return $entity;
    }

    public function trashUuid($id)
    {
        $uuid = $this->getUuid($id);
        $uuid->setTrashed(true);
        
        $this->getEventManager()->trigger('trash', $this, [
            'object' => $uuid
        ]);
        
        $this->persist($uuid);
        return $this;
    }

    public function restoreUuid($id)
    {
        $uuid = $this->getUuid($id);
        $uuid->setTrashed(false);
        
        $this->getEventManager()->trigger('restore', $this, [
            'object' => $uuid
        ]);
        
        $this->persist($uuid);
        return $this;
    }

    public function createUuid()
    {
        $entity = $this->getClassResolver()->resolve('Uuid\Entity\UuidInterface');
        
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush($entity);
        
        return $entity;
    }

    public function createService($idOrObject)
    {
        $holder = $this->ambigousToUuid($idOrObject)->getHolder();
        foreach ($this->getOption('resolver') as $className => $callback) {
            if ($holder instanceof $className)
                return $callback($holder, $this->getServiceLocator());
        }
        return $holder;
    }

    public function flush()
    {
        $this->getObjectManager()->flush();
        return $this;
    }

    public function persist($object)
    {
        $this->getObjectManager()->persist($object);
        return $this;
    }

    protected function ambigousToUuid($idOrObject)
    {
        $uuid = NULL;
        
        if (is_int($idOrObject)) {
            $uuid = $this->getUuid($idOrObject);
        } elseif ($idOrObject instanceof UuidHolder) {
            $uuid = $idOrObject->getUuidEntity();
        } elseif ($idOrObject instanceof UuidInterface) {
            $uuid = $idOrObject;
        } else {
            throw new Exception\InvalidArgumentException(sprintf('Expected int, UuidHolder or UuidInterface but got "%s"', (is_object($idOrObject) ? get_class($idOrObject) : gettype($idOrObject))));
        }
        
        return $uuid;
    }
}