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
use Doctrine\Common\Collections\Criteria;

class LinkPlugin extends AbstractPlugin
{
    use \Link\Manager\LinkManagerAwareTrait,\Link\Service\LinkServiceAwareTrait;

    public function getEntityType ()
    {
        return $this->getOption('to_type');
    }

    public function getLinkService ()
    {
        $this->getLinkManager()->add($this->getEntityService()
            ->getEntity());
        $this->getLinkManager()->get();
    }

    public function getChildren ()
    {
        $linkService = $this->getComponent('link');
        
        return $linkService->getChildren();
    }

    public function getParents ()
    {
        return $this->getLinkService()->getParents();
    }

    public function findChildren ($entityType = NULL)
    {
        if ($entityType === NULL)
            $entityType = $this->getEntityType();
        
        $criteria = Criteria::create()->where(Criteria::expr()->eq("type", $entityType))
            ->setFirstResult(0);
        $return = $this->getLinkService()
            ->getChildren()
            ->matching($criteria);
        return $return;
    }

    public function findParents ($entityType = NULL)
    {
        if ($entityType === NULL)
            $entityType = $this->getEntityType();
        $criteria = Criteria::create()->where(Criteria::expr()->eq("type", $entityType))
            ->setFirstResult(0);
        $return = $this->getLinkService()
            ->getParents()
            ->matching($criteria);
        return $return;
    }

    public function findParent ($entityType = NULL)
    {
        if ($entityType === NULL)
            $entityType = $this->getEntityType();
        $criteria = Criteria::create()->where(Criteria::expr()->eq("type", $entityType))
            ->setFirstResult(0);
        $return = $this->getLinkService()
            ->getParents()
            ->matching($criteria)
            ->current();
        return $return;
    }

    public function findChild ($entityType = NULL)
    {
        if ($entityType === NULL)
            $entityType = $this->getEntityType();
        $criteria = Criteria::create()->where(Criteria::expr()->eq("type", $entityType))
            ->setFirstResult(0);
        $return = $this->getLinkService()
            ->getChildren()
            ->matching($criteria)
            ->current();
        return $return;
    }
    
    /*
     *
     * protected
     * function
     * findByFactoryClassName(Collection
     * $collection,
     * $factoryClassName){
     * $results
     * =
     * array();
     * $currentDepth
     * =
     * 1;
     * $collection->first();
     * foreach($collection->toArray()
     * as
     * $entity){
     * if($entity->get('factory')->get('className')
     * ==
     * $factoryClassName
     * ){
     * $results[]
     * =
     * $this->_factory($entity);
     * }
     * }
     * return
     * $results;
     * }
     */
}