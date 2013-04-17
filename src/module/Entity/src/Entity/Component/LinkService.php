<?php
namespace Entity\Component;

use Entity\Service\EntityServiceInterface;
use Doctrine\Common\Collections\Criteria;

class LinkService
{

    protected $_entityService;

    function __construct (EntityServiceInterface $entityService)
    {
        $this->_entityService = $entityService;
    }

    public function addParent (EntityServiceInterface $entityService)
    {}

    public function addChild (EntityServiceInterface $entityService)
    {
    	$this->_entityService->getEntity()->add($entityService->getEntity());
    }

    public function hasChild ($childId)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("child", $childId))
            ->setMaxResults(1);
    	return $this->getChildren($criteria);
    }

    public function hasParent ($parentId)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("parent", $parentId))
            ->setMaxResults(1);
    	return $this->getParents($criteria);
    }

    public function getChildren (Criteria $criteria = NULL)
    {
        $entityService = $this->_entityService;
        $links = $entityService->get('children');
        return $criteria ? $links->matching($criteria) : $links;
    }

    public function getParents (Criteria $criteria = NULL)
    {
        $entityService = $this->_entityService;
        $links = $entityService->get('parents');
        return $criteria ? $links->matching($criteria) : $links;
    }
    
    public function getChild (Criteria $criteria) {
        return current($this->getChildren($criteria));
    }
    
    public function getParent (Criteria $criteria) {
        return current($this->getChildren($criteria));
    }
}