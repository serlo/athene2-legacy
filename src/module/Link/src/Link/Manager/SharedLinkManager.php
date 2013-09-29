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
namespace Link\Manager;

use Link\Entity\LinkTypeInterface;
use Link\Exception;
use Link\Entity;

class SharedLinkManager extends AbstractManager implements SharedLinkManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait;

    public function findLinkManagerByName($name, $repositoryName)
    {
        if (! is_string($name))
            throw new Exception\InvalidArgumentException(sprintf('$name expects string but got `%s`', gettype($name)));
        
        $entity = $this->getObjectManager()
            ->getRepository($repositoryName)
            ->findOneByName($name);
        
        if (! is_object($entity))
            throw new Exception\RuntimeException(sprintf('`%s` not found in repository `%s`', $name, $repositoryName));
        
        if (! $entity instanceof Entity\LinkTypeInterface)
            throw new Exception\RuntimeException(sprintf('`%s` does not implement `Link\Entity\LinkTypeInterface`', get_class($entity)));
        
        if (! $this->hasInstance($entity->getId())) {
            $this->addInstance($entity->getId(), $this->createService($entity));
        }
        
        return $this->getInstance($entity->getId());
    }

    public function getLinkManager($id, $repositoryName)
    {
        if (! is_numeric($id))
            throw new Exception\InvalidArgumentException(sprintf('$name expects numeric but got `%s`', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            
            $entity = $this->getObjectManager()->find($repositoryName, $id);
            
            if (! is_object($entity))
                throw new Exception\RuntimeException(sprintf('`%s` not found in repository `%s`', $id, $repositoryName));
            
            if (! $entity instanceof Entity\LinkTypeInterface)
                throw new Exception\RuntimeException(sprintf('`%s` does not implement `Link\Entity\LinkTypeInterface`', get_class($entity)));
            
            $this->createService($entity);
            $this->addInstance($entity->getId(), $this->createService($entity));
        }
        
        return $this->getInstance($id);
    }

    protected function createService(LinkTypeInterface $type)
    {
        $instance = $this->createInstance('Link\Manager\LinkManagerInterface');
        $instance->setEntity($type);
        return $instance;
    }
}