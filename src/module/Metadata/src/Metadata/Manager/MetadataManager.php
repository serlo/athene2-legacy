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
namespace Metadata\Manager;

use Metadata\Exception;

class MetadataManager implements MetadataManagerInterface
{
    use\Common\Traits\InstanceManagerTrait,\Common\Traits\ObjectManagerAwareTrait;

    public function getMetadata($id)
    {
        if (! is_numeric($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $className = $this->getClassResolver()->resolveClassName('Metadata\Entity\MetadataInterface');
            $entity = $this->getObjectManager()->find($className, $id);
            
            if (! is_object($entity))
                throw new Exception\MetadataNotFoundException(sprintf('Could not find metadata by id `%s`', $id));
            
            $this->addInstance($id, $entity);
        }
        
        return $this->getInstance($id);
    }

    public function findMetadataByObject(\Uuid\Entity\UuidInterface $object)
    {
        $className = $this->getClassResolver()->resolveClassName('Metadata\Entity\MetadataInterface');
        return $this->getObjectManager()
            ->getRepository($className)
            ->findBy(array(
            'object' => $object->getId()
        ));
    }

    public function addMetadata(\Uuid\Entity\UuidInterface $object, $key, $value)
    {
        if (! is_string($key))
            throw new Exception\InvalidArgumentException(sprintf('Expected parameter 2 to be string but got %s', gettype($key)));
        if (! is_string($value))
            throw new Exception\InvalidArgumentException(sprintf('Expected parameter 3 to be string but got %s', gettype($value)));
    }

    public function findMetadataByObjectAndKey(\Uuid\Entity\UuidInterface $object, $key, $default = NULL)
    {
        if (! is_string($key))
            throw new Exception\InvalidArgumentException(sprintf('Expected parameter 2 to be string but got %s', gettype($key)));
        
        $key = $this->findKeyByName($key);
        
        $className = $this->getClassResolver()->resolveClassName('Metadata\Entity\MetadataInterface');
        
        return $this->getObjectManager()
            ->getRepository($className)
            ->findBy(array(
            'object' => $object->getId(),
            'key' => $key->getId()
        ));
    }

    /**
     *
     * @param string $name            
     * @return \Metadata\Entity\MetadataInterface
     */
    protected function findKeyByName($name)
    {
        $className = $this->getClassResolver()->resolveClassName('Metadata\Entity\MetadataInterface');
        $entity = $this->getObjectManager()
            ->getRepository($className)
            ->findBy(array(
            'name' => $name
        ));
        if (! is_object($entity))
            throw new Exception\RuntimeException(sprintf('Could not find a metadata key by name `%s`', $name));
        return $entity;
    }
}