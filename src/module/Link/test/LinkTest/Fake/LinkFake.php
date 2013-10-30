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
namespace LinkTest\Fake;

use Link\Entity\LinkableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Link\Entity\LinkTypeInterface;

/**
 * @codeCoverageIgnore
 */
class LinkFake implements LinkableInterface
{

    protected $children, $parents, $id;

    /**
     *
     * @return field_type $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param field_type $id            
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->parents = new ArrayCollection();
    }
    
    /*
     * (non-PHPdoc) @see LinkableInterface::getChildren()
     */
    public function getChildren(LinkTypeInterface $type)
    {
        return $this->children;
    }
    
    /*
     * (non-PHPdoc) @see LinkableInterface::getParents()
     */
    public function getParents(LinkTypeInterface $type)
    {
        return $this->parents;
    }
    
    /*
     * (non-PHPdoc) @see LinkableInterface::addChild()
     */
    public function addChild(LinkableInterface $parent, LinkTypeInterface $type)
    {
        $this->children->add($parent);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see LinkableInterface::addParent()
     */
    public function addParent(LinkableInterface $parent, LinkTypeInterface $type)
    {
        $this->parent->add($parent);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see LinkableInterface::removeChild()
     */
    public function removeChild(LinkableInterface $parent, LinkTypeInterface $type)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see LinkableInterface::removeParent()
     */
    public function removeParent(LinkableInterface $parent, LinkTypeInterface $type)
    {
        // TODO Auto-generated method stub
    }
    /*
     * (non-PHPdoc) @see LinkableInterface::positionChild()
     */
    public function positionChild(LinkableInterface $child, LinkTypeInterface $type, $position)
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see LinkableInterface::positionParent()
     */
    public function positionParent(LinkableInterface $parent, LinkTypeInterface $type, $position)
    {
        // TODO Auto-generated method stub
    }
    /*
     * (non-PHPdoc) @see \Link\Entity\LinkableInterface::getType()
     */
    public function getType()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Link\Entity\LinkableInterface::setType()
     */
    public function setType(LinkTypeInterface $type)
    {
        // TODO Auto-generated method stub
    }
}
