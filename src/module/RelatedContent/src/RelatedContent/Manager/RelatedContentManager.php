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
namespace RelatedContent\Manager;


use RelatedContent\Exception;
use RelatedContent\Result;
use Doctrine\Common\Collections\ArrayCollection;
use RelatedContent\Entity\ContainerInterface;
use RelatedContent\Entity;

class RelatedContentManager implements RelatedContentManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait, \Uuid\Manager\UuidManagerAwareTrait, \Common\Traits\RouterAwareTrait;

    public function getContainer($id)
    {
        if (! is_int($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected integer but got `%d`', $id));
        
        $className = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\ContainerInterface');
        
        /* @var $related Entity\ContainerInterface */
        $related = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($related)){
            return $this->createContainer($id);
        }
        //throw new Exception\RelationNotFoundException(sprintf('Could not find a Container by ID `%d`', $id));
        
        return $related;
    }
    
    /*
     * (non-PHPdoc) @see \Related\ContainerInterface::getRelatedContainer()
     */
    public function aggregateRelatedContent($id)
    {
        if (! is_int($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected integer but got `%d`', $id));
        
        if (! $this->hasInstance($id)) {
            $related = $this->getContainer($id);
            
            $results = $this->aggregate($related);
            
            $this->addInstance($id, $results);
        }
        
        return $this->getInstance($id);
    }

    public function addExternalRelation($container, $title, $url)
    {
        $container = $this->getContainer($container);
        /* @var $external Entity\ExternalInterface */
        $external = $this->getClassResolver()->resolve('RelatedContent\Entity\ExternalInterface');
        $external->setTitle($title);
        $external->setContainer($container);
        $external->setUrl($url);
        $this->getObjectManager()->persist($external);
        return $external;
    }

    public function addInternalRelation($container, $title, $reference)
    {
        $object = $this->getUuidManager()->getUuid($reference);
        $container = $this->getContainer($container);
        /* @var $internal Entity\InternalInterface */
        $internal = $this->getClassResolver()->resolve('RelatedContent\Entity\InternalInterface');
        $internal->setTitle($title);
        $internal->setContainer($container);
        $internal->setReference($object);
        $this->getObjectManager()->persist($internal);
        return $internal;
    }

    public function removeExternalRelation($id)
    {
        $className = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\InternalInterface');
        /* @var $object Entity\InternalInterface */
        $object = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($object))
            throw new Exception\RelationNotFoundException(sprintf('Could not find internal by id `%d`', $id));
        
        $this->getObjectManager()->remove($object);
        return $this;        
    }

    public function removeInternalRelation($id)
    {
        $className = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\ExternalInterface');
        /* @var $object Entity\ExternalInterface */
        $object = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($object))
            throw new Exception\RelationNotFoundException(sprintf('Could not find external by id `%d`', $id));
        
        $this->getObjectManager()->remove($object);
        return $this;          
    }
    
    protected function createContainer($id){
        $uuid = $this->getUuidManager()->getUuid($id);
        $related = $this->getClassResolver()->resolve('RelatedContent\Entity\ContainerInterface');
        $this->getUuidManager()->injectUuid($related, $uuid);
        $this->getObjectManager()->persist($related);
        return $related;
    }

    protected function aggregate(ContainerInterface $related)
    {
        $collection = new ArrayCollection();
        
        foreach ($related->getInternalRelations() as $relation) {
            $result = new Result\InternalResult();
            $result->setObject($relation);
            $result->setRouter($this->getRouter());
            $collection->add($result);
        }
        
        foreach ($related->getExternalRelations() as $relation) {
            $result = new Result\ExternalResult();
            $result->setObject($relation);
            $collection->add($result);
        }
        
        return $collection;
    }
}