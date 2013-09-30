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
namespace LearningResource\Plugin\Link;

use Entity\Plugin\AbstractPlugin;
use Entity\Collection\EntityCollection;

class LinkPlugin extends AbstractPlugin
{
    use \Link\Manager\LinkManagerAwareTrait,\Link\Service\LinkServiceAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'types' => array()
        );
    }

    public function isOneToOne()
    {
        return $this->getOption('association') == 'one-to-one';
    }

    public function getEntityTypes()
    {
        return $this->getOption('types');
    }

    public function getLinkService()
    {
        return $this->getLinkManager()->getLink($this->getEntityService()
            ->getEntity());
    }

    protected function clearAssociation()
    {
        if ($this->isOneToOne() && $this->hasParent()) {
            $this->getLinkService()->removeParent($this->getParent());
        } elseif ($this->isOneToOne() && $this->hasChild()) {
            $this->getLinkService()->removeChild($this->getChild());
        }
    }

    public function addParent($entity)
    {
        if (! in_array($entity->getType()->getName(), $this->getEntityTypes()))
            throw new \ErrorException();
        
        $this->clearAssociation();
        
        $this->getLinkService()->addParent($entity->getEntity());
        
        return $this;
    }

    public function addChild($entity)
    {
        if (! in_array($entity->getType()->getName(), $this->getEntityTypes()))
            throw new \ErrorException();
        
        $this->clearAssociation();
        
        $this->getLinkService()->addParent($entity->getEntity());
        
        return $this;
    }

    public function hasChild()
    {
        return is_object($this->findChild());
    }

    public function hasChildren()
    {
        return $this->findChildren()->count();
    }

    public function hasParents()
    {
        return $this->findParents()->count();
    }

    public function hasParent()
    {
        return is_object($this->findParent());
    }

    public function findChildren(array $entityTypes = NULL)
    {
        if ($this->isOneToOne())
            throw new \ErrorException('Link allows only one-to-one associations');
        
        if ($entityTypes === NULL)
            $entityTypes = $this->getEntityTypes();
        
        $collection = $this->getLinkService()
            ->getChildren()
            ->filter(function ($e) use($entityTypes)
        {
            return (in_array($e->getType()
                ->getName(), $entityTypes));
        });
        
        return new EntityCollection($collection, $this->getEntityManager());
    }

    public function findParents(array $entityTypes = NULL)
    {
        if ($this->isOneToOne())
            throw new \ErrorException('Link allows only one-to-one associations');
        
        if ($entityTypes === NULL)
            $entityTypes = $this->getEntityTypes();
        
        $manager = $this->getEntityManager();
        
        $collection = $this->getLinkService()
            ->getParents()
            ->filter(function ($e) use($entityTypes)
        {
            return (in_array($e->getType()
                ->getName(), $entityTypes));
        });
        
        return new EntityCollection($collection, $this->getEntityManager());
    }

    public function findParent($entityTypes = NULL)
    {
        if (! $this->isOneToOne())
            throw new \ErrorException('Link doesn\'t allow one-to-one associations');
        
        if ($entityTypes === NULL)
            $entityTypes = $this->getEntityTypes();
        
        $manager = $this->getEntityManager();
        
        $collection = $this->getLinkService()
            ->getParents()
            ->filter(function ($e) use($entityTypes)
        {
            return (in_array($e->getType()
                ->getName(), $entityTypes));
        });
        $collection = new EntityCollection($collection, $this->getEntityManager());
        
        return $collection->current();
    }

    public function findChild($entityTypes = NULL)
    {
        if (! $this->isOneToOne())
            throw new \ErrorException('Link doesn\'t allow one-to-one associations');
        
        if ($entityTypes === NULL)
            $entityTypes = $this->getEntityTypes();
        
        $manager = $this->getEntityManager();
        
        $collection = $this->getLinkService()
            ->getChildren()
            ->filter(function ($e) use($entityTypes)
        {
            return (in_array($e->getType()
                ->getName(), $entityTypes));
        });
        $collection = new EntityCollection($collection, $this->getEntityManager());
        
        return $collection->current();
    }
	/* (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach (\Zend\EventManager\EventManagerInterface $events)
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::detach()
     */
    public function detach (\Zend\EventManager\EventManagerInterface $events)
    {
        // TODO Auto-generated method stub
        
    }

}