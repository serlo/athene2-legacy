<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Link\Entity;

use Doctrine\Common\Collections\Collection;

interface LinkableInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     * Returns the children
     *
     * @param LinkTypeInterface $type            
     * @return Collection
     */
    public function getChildren(LinkTypeInterface $type);

    /**
     * Returns the parents
     *
     * @param LinkTypeInterface $type            
     * @return Collection
     */
    public function getParents(LinkTypeInterface $type);

    /**
     * Adds a child
     *
     * @param LinkableInterface $parent            
     * @param LinkTypeInterface $type            
     * @return $this
     */
    public function addChild(LinkableInterface $parent, LinkTypeInterface $type);

    /**
     * Adds a parent
     *
     * @param LinkableInterface $parent            
     * @param LinkTypeInterface $type            
     * @return $this
     */
    public function addParent(LinkableInterface $parent, LinkTypeInterface $type);

    /**
     *
     * @param LinkableInterface $parent            
     * @param LinkTypeInterface $type            
     * @return $this
     */
    public function removeChild(LinkableInterface $parent, LinkTypeInterface $type);

    /**
     *
     * @param LinkableInterface $parent            
     * @param LinkTypeInterface $type            
     * @return $this
     */
    public function removeParent(LinkableInterface $parent, LinkTypeInterface $type);

    /**
     *
     * @param LinkableInterface $child            
     * @param LinkTypeInterface $type            
     * @param int $position            
     * @return $this
     */
    public function positionChild(LinkableInterface $child, LinkTypeInterface $type, $position);

    /**
     *
     * @param LinkableInterface $parent            
     * @param LinkTypeInterface $type            
     * @param int $position            
     * @return $this
     */
    public function positionParent(LinkableInterface $parent, LinkTypeInterface $type, $position);
}