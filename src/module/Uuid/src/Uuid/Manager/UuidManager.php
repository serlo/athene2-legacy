<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Uuid\Manager;

use Uuid\Entity\UuidHolder;
use Uuid\Entity\UuidInterface;

class UuidManager implements UuidManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait;

    public function inject (UuidHolder $entity, UuidInterface $uuid = NULL)
    {
        if (! $uuid) {
            $uuid = $this->create();
        }
        return $entity->setUuid($uuid);
    }

    public function get ($key)
    {
        if (is_numeric($key)) {
            $entity = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Uuid\Entity\UuidInterface'), (int) $key);
        } elseif (is_string($key)) {
            $entity = $this->getObjectManager()
                ->getRepository($this->getClassResolver()
                ->resolveClassName('Uuid\Entity\UuidInterface'))
                ->findOneByUuid((string) $key);
        } elseif ($key instanceof \Uuid\Entity\UuidInterface) {
            $entity = $key;
        } else
            throw new \InvalidArgumentException();
        
        if (! is_object($entity))
            throw new \Exception('not found');
        
        $this->addInstance($entity->getId(), $entity);
        return $entity;
    }

    public function create ()
    {
        $entity = $this->createInstance('Uuid\Entity\UuidInterface');
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush($entity);
        $this->addInstance($entity->getId(), $entity);
        return $entity;
    }
}