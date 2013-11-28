<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Link\Service;

use Link\Entity\LinkableInterface;
use Doctrine\Common\Collections\Collection;

interface LinkServiceInterface
{

    /**
     * Returns the children
     *
     * @return Collection
     */
    public function getChildren();

    /**
     * Returns the parents
     *
     * @return Collection
     */
    public function getParents();

    /**
     * Adds a parent
     *
     * @param LinkServiceInterface|LinkEntityInterface $child            
     * @return $this
     */
    public function addParent($parent);

    /**
     * Adds a child
     *
     * @param LinkServiceInterface|LinkEntityInterface $child            
     * @return $this
     */
    public function addChild($child);

    /**
     *
     * @param LinkServiceInterface|LinkEntityInterface $child            
     * @return $this
     */
    public function removeChild($child);

    /**
     *
     * @param LinkServiceInterface|LinkEntityInterface $parent            
     * @return $this
     */
    public function removeParent($parent);

    /**
     *
     * @param int $id            
     * @return LinkableInterface
     */
    public function getParent($id);

    /**
     *
     * @param int $id            
     * @return LinkableInterface
     */
    public function getChild($id);

    /**
     *
     * @param array $children            
     * @return $this
     */
    public function orderChildren(array $children);

    /**
     *
     * @param array $parents            
     * @return $this
     */
    public function orderParents(array $parents);
}