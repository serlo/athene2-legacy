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
use Doctrine\Common\Collections\ArrayCollection;
use Uuid\Collection\UuidCollection;

class UuidManager implements UuidManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait;
    use\Common\Traits\ConfigAwareTrait;

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
        $collection = new ArrayCollection($entities);
        return new UuidCollection($collection, $this);
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
        if (! is_numeric($key))
            throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($key)));
        
        if (! $this->hasInstance($key)) {
            $entity = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Uuid\Entity\UuidInterface'), (int) $key);
            
            if (! is_object($entity))
                throw new NotFoundException(sprintf('Could not find %s', $key));
            
            $this->addInstance($entity->getId(), $entity);
        }
        
        return $this->getInstance($key);
    }

    public function findUuidByName($string)
    {
        if (! is_string($string))
            throw new InvalidArgumentException(sprintf('Expected string but got %s', gettype($string)));
        
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Uuid\Entity\UuidInterface'))
            ->findOneBy(array(
            'uuid' => (string) $string
        ));
        
        if (! $entity)
            throw new NotFoundException(sprintf('Could not find %s', $string));
        
        if (! $this->hasInstance($entity->getId())) {
            $this->addInstance($entity->getId(), $entity);
        }
        
        return $this->getUuid($entity->getId());
    }

    public function getService($key)
    {
        $key = $this->getUuid($key)->getHolder();
        foreach ($this->getOption('resolver') as $className => $callback) {
            if ($key instanceof $className)
                return $callback($key, $this->getServiceLocator());
        }
        return $key;
    }

    public function createUuid()
    {
        $entity = $this->createInstance('Uuid\Entity\UuidInterface');
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush($entity);
        $this->addInstance($entity->getId(), $entity);
        return $entity;
    }
}