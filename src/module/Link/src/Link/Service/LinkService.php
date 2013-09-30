<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Link\Service;

use Link\Entity\LinkEntityInterface;

class LinkService implements LinkServiceInterface
{
    
    use\Common\Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait,\Link\Manager\LinkManagerAwareTrait,\Common\Traits\EntityDelegatorTrait;

    public function setEntity(LinkEntityInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function removeChild($child)
    {
        if (! ($child instanceof LinkServiceInterface || $child instanceof LinkEntityInterface))
            throw new \InvalidArgumentException();
        
        if ($child instanceof LinkServiceInterface)
            $child = $child->getEntity();
        
        $this->getEntity()->removeChild($child, $this->getLinkManager()
            ->getEntity());
    }

    public function removeParent($parent)
    {
        if (! ($parent instanceof LinkServiceInterface || $parent instanceof LinkEntityInterface))
            throw new \InvalidArgumentException();
        
        if ($parent instanceof LinkServiceInterface)
            $parent = $parent->getEntity();
        
        $this->getEntity()->removeParent($parent, $this->getLinkManager()
            ->getEntity());
    }

    public function getChildren()
    {
        return $this->getEntity()->getChildren($this->getLinkManager()
            ->getEntity());
    }

    public function getParents()
    {
        return $this->getEntity()->getParents($this->getLinkManager()
            ->getEntity());
    }

    public function addParent($parent, $order = NULL)
    {
        if (! ($parent instanceof LinkServiceInterface || $parent instanceof LinkEntityInterface))
            throw new \InvalidArgumentException();
        
        if ($parent instanceof LinkServiceInterface)
            $parent = $parent->getEntity();

        $this->getEntity()->addParent($parent, $this->getLinkManager()
            ->getEntity(), $order);
        
        return $this;
    }
    
    public function addChild($child, $oder = NULL)
    {
        if (! ($child instanceof LinkServiceInterface || $child instanceof LinkEntityInterface))
            throw new \InvalidArgumentException();
        
        if ($child instanceof LinkServiceInterface)
            $child = $child->getEntity();

        $this->getEntity()->addChild($child, $this->getLinkManager()
            ->getEntity(), $oder);
        
        return $this;
    }
}