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
use Core\Entity\AbstractEntityAdapter;
use Doctrine\Common\Collections\Criteria;

class LinkService implements LinkServiceInterface
{
    
    use \Common\Traits\ObjectManagerAwareTrait, \ClassResolver\ClassResolverAwareTrait, \Link\Manager\LinkManagerAwareTrait, \Common\Traits\EntityDelegatorTrait;
    
    public function setEntity(LinkEntityInterface $entity)
    {
        return parent::setEntity($entity);
    }
    
    /*
     * (non-PHPdoc) @see \Link\Service\LinkServiceInterface::getChildren()
     */
    public function getChildren()
    {
        return $this->getEntity()->getChildren()->matching(Criteria::create(Criteria::expr()->eq('type', $this->getLinkManager()->getEntity()->getId())));
    }
    
    /*
     * (non-PHPdoc) @see \Link\Service\LinkServiceInterface::getParents()
     */
    public function getParents()
    {
        return $this->getEntity()->getParents()->matching(Criteria::create(Criteria::expr()->eq('type', $this->getLinkManager()->getEntity()->getId())));
    }
    
    /*
     * (non-PHPdoc) @see \Link\Service\LinkServiceInterface::addParent()
     */
    public function addParent($parent)
    {
        if (! ($parent instanceof LinkServiceInterface || $parent instanceof LinkEntityInterface))
            throw new \InvalidArgumentException();
        
        if ($parent instanceof LinkServiceInterface)
            $parent = $parent->getEntity();
        
        $this->getEntity()->addParent($parent, $this->getLinkManager()->getEntity());
        //$parent->getChildren()->add($this->getEntity());
        
        
        
        return $this->flush();
    }
    
    /*
     * (non-PHPdoc) @see \Link\Service\LinkServiceInterface::addChild()
     */
    public function addChild($child)
    {
        if (! ($child instanceof LinkServiceInterface || $child instanceof LinkEntityInterface))
            throw new \InvalidArgumentException();
        
        if ($child instanceof LinkServiceInterface)
            $child = $child->getEntity();
        
        $this->getEntity()->addChild($child, $this->getLinkManager()->getEntity());
        //$child->getParents()->add($this->getEntity());
        
        return $this->flush();
    }

    protected function flush()
    {
        $this->getObjectManager()->flush();
        return $this;
    }
}