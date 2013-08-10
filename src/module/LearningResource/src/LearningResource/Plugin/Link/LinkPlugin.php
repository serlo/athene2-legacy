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
use Entity\Service\EntityServiceInterface;

class LinkPlugin extends AbstractPlugin
{
    use \Link\Manager\LinkManagerAwareTrait,\Link\Service\LinkServiceAwareTrait,\Entity\Manager\EntityManagerAwareTrait;

    public function getEntityType()
    {
        return $this->getOption('to_type');
    }

    public function getLinkService()
    {
        return $this->getLinkManager()
            ->add($this->getEntityService()
            ->getEntity())
            ->get($this->getEntityService()
            ->getEntity());
    }

    public function addParent($entity)
    {
        $this->getLinkService()->addParent($entity->getEntity());
        
        return $this;
    }

    public function addChild($entity)
    {
        $this->getLinkService()->addParent($entity->getEntity());
        
        return $this;
    }

    public function getChildren()
    {
        return $this->getLinkService()->getChildren();
    }

    public function getParents()
    {
        return $this->getLinkService()->getParents();
    }

    public function findChildren($entityType = NULL)
    {
        if ($entityType === NULL)
            $entityType = $this->getEntityType();
        
        $manager = $this->getEntityManager();
        
        $return = $this->getLinkService()
            ->getChildren()
            ->map(function ($e) use($entityType, $manager)
        {
            $return = ($e->getType()
                ->getName() == $entityType) ? $manager->get($e) : null;
            return $return;
        });
        
        return $return;
    }

    public function findParents($entityType = NULL)
    {
        if ($entityType === NULL)
            $entityType = $this->getEntityType();
        
        $manager = $this->getEntityManager();
        
        $return = $this->getLinkService()
            ->getParents()
            ->map(function ($e) use($entityType, $manager)
        {
            $return = ($e->getType()
                ->getName() == $entityType) ? $manager->get($e) : null;
            return $return;
        });
        
        return $return;
    }

    public function findParent($entityType = NULL)
    {
        if ($entityType === NULL)
            $entityType = $this->getEntityType();
        
        $manager = $this->getEntityManager();
        
        $return = $this->getLinkService()
            ->getParents()
            ->map(function ($e) use($entityType, $manager)
        {
            $return = ($e->getType()
                ->getName() == $entityType) ? $manager->get($e) : null;
            return $return;
        });
        
        return $return->current();
    }

    public function findChild($entityType = NULL)
    {
        if ($entityType === NULL)
            $entityType = $this->getEntityType();
        
        $manager = $this->getEntityManager();
        
        $return = $this->getLinkService()
            ->getChildren()
            ->map(function ($e) use($entityType, $manager)
        {
            $return = ($e->getType()
                ->getName() == $entityType) ? $manager->get($e) : null;
            return $return;
        });
        
        return $return->current();
    }
    
    /*
     * protected function findByFactoryClassName (Collection $collection, $factoryClassName) { $results = array(); $currentDepth = 1; $collection->first(); foreach ($collection->toArray() as $entity) { if ($entity->get('factory')->get('className') == $factoryClassName) { $results[] = $this->_factory($entity); } } return $results; }
     */
}