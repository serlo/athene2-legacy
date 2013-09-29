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

use Link\Entity\LinkEntityInterface;
use Doctrine\Common\Collections\ArrayCollection;

class LinkFake implements LinkEntityInterface
{
    protected $children, $parents;
    
    public function __construct(){
        $this->children = new ArrayCollection();
        $this->parents = new ArrayCollection();
    }
    
	/* (non-PHPdoc)
     * @see \Link\Entity\LinkEntityInterface::getChildren()
     */
    public function getChildren (\Link\Entity\LinkTypeInterface $type)
    {
        return $this->children;
    }

	/* (non-PHPdoc)
     * @see \Link\Entity\LinkEntityInterface::getParents()
     */
    public function getParents (\Link\Entity\LinkTypeInterface $type)
    {
        return $this->parents;
    }

	/* (non-PHPdoc)
     * @see \Link\Entity\LinkEntityInterface::addChild()
     */
    public function addChild (\Link\Entity\LinkEntityInterface $parent,\Link\Entity\LinkTypeInterface $type)
    {
        $this->children->add($parent);
        return $this;
    }

	/* (non-PHPdoc)
     * @see \Link\Entity\LinkEntityInterface::addParent()
     */
    public function addParent (\Link\Entity\LinkEntityInterface $parent,\Link\Entity\LinkTypeInterface $type)
    {
        $this->parent->add($parent);
        return $this;
    }

}
