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
use Doctrine\Common\Collections\ArrayCollection;
use RelatedContent\Entity\ContainerInterface;
use RelatedContent\Entity;
use RelatedContent\Result\InternalResult;
use RelatedContent\Result\ExternalResult;
use RelatedContent\Result\CategoryResult;

class RelatedContentManager implements RelatedContentManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\Common\Traits\RouterAwareTrait;

    public function getContainer($id)
    {
        if (! is_int($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected integer but got `%d`', $id));
        
        $className = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\ContainerInterface');
        
        /* @var $related Entity\ContainerInterface */
        $related = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($related)) {
            return $this->createContainer($id);
        }
        // throw new Exception\RelationNotFoundException(sprintf('Could not find a Container by ID `%d`', $id));
        
        return $related;
    }
    
    /*
     * (non-PHPdoc) @see \Related\ContainerInterface::getRelatedContainer()
     */
    public function aggregateRelatedContent($id)
    {
        $related = $this->getContainer($id);
        
        return $results = $this->aggregate($related);
    }

    public function addExternal($container, $title, $url)
    {
        $container = $this->getContainer($container);
        $holder = $this->createHolder($container);
        /* @var $external Entity\ExternalInterface */
        $external = $this->getClassResolver()->resolve('RelatedContent\Entity\ExternalInterface');
        $external->setTitle($title);
        $external->setHolder($holder);
        $external->setUrl($url);
        $this->getObjectManager()->persist($external);
        return $external;
    }

    public function addInternal($container, $title, $reference)
    {
        $object = $this->getUuidManager()->getUuid($reference);
        $container = $this->getContainer($container);
        $holder = $this->createHolder($container);
        /* @var $internal Entity\InternalInterface */
        $internal = $this->getClassResolver()->resolve('RelatedContent\Entity\InternalInterface');
        $internal->setTitle($title);
        $internal->setHolder($holder);
        $internal->setReference($object);
        $this->getObjectManager()->persist($internal);
        return $internal;
    }

    public function addCategory($container, $name)
    {
        $container = $this->getContainer($container);
        $holder = $this->createHolder($container);
        /* @var $internal Entity\CategoryInterface */
        $internal = $this->getClassResolver()->resolve('RelatedContent\Entity\CategoryInterface');
        $internal->setTitle($name);
        $internal->setHolder($holder);
        $this->getObjectManager()->persist($internal);
        return $internal;
    }

    public function removeRelatedContent($id)
    {
        $className = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\HolderInterface');
        /* @var $object Entity\HolderInterface */
        $object = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($object))
            throw new Exception\RelationNotFoundException(sprintf('Could not find internal by id `%d`', $id));
        
        $this->getObjectManager()->remove($object);
        return $this;
    }

    public function positionHolder($holder, $position)
    {
        if (! is_int($holder))
            throw new Exception\InvalidArgumentException(sprintf('Expected integer but got `%d`', $holder));
        
        $className = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\HolderInterface');
        /* @var $holder Entity\HolderInterface */
        $holder = $this->getObjectManager()->find($className, $holder);
        
        if (! is_object($holder))
            throw new Exception\RuntimeException(sprintf('Holder not found by id `%d`', $holder));
        
        $holder->setPosition($position);
        
        $this->getObjectManager()->persist($holder);
        return $this;
    }

    /**
     *
     * @param int $id            
     * @return Entity\ContainerInterface
     */
    protected function createContainer($id)
    {
        $uuid = $this->getUuidManager()->getUuid($id);
        /* @var $container Entity\ContainerInterface */
        $container = $this->getClassResolver()->resolve('RelatedContent\Entity\ContainerInterface');
        $this->getUuidManager()->injectUuid($container, $uuid);
        $this->getObjectManager()->persist($container);
        return $container;
    }

    /**
     *
     * @param Entity\ContainerInterface $container            
     * @return Entity\HolderInterface
     */
    protected function createHolder(Entity\ContainerInterface $container)
    {
        /* @var $holder Entity\HolderInterface */
        $holder = $this->getClassResolver()->resolve('RelatedContent\Entity\HolderInterface');
        $holder->setContainer($container);
        $holder->setPosition(999);
        $this->getObjectManager()->persist($holder);
        $this->getObjectManager()->flush($holder);
        return $holder;
    }

    /**
     *
     * @param ContainerInterface $related            
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    protected function aggregate(ContainerInterface $related)
    {
        $collection = new ArrayCollection();
        foreach ($related->getHolders() as $holder) {
            $specific = $holder->getSpecific();
            if ($specific instanceof Entity\InternalInterface) {
                $result = new InternalResult();
                $result->setObject($specific);
                $result->setRouter($this->getRouter());
            } elseif ($specific instanceof Entity\ExternalInterface) {
                $result = new ExternalResult();
                $result->setObject($specific);
            } elseif ($specific instanceof Entity\CategoryInterface) {
                $result = new CategoryResult();
                $result->setObject($specific);
            } else {
                throw new Exception\RuntimeException(sprintf('Could not find a result type for `%s`', get_class($specific)));
            }
            $collection->add($result);
        }
        return $collection;
    }
}