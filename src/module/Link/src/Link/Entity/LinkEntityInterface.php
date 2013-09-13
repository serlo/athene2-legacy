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

interface LinkEntityInterface
{

    /**
     * Returns
     * the
     * children
     *
     * @param LinkTypeInterface $type
     * @return Collection
     */
    public function getChildren (LinkTypeInterface $type);

    /**
     * Returns
     * the
     * parents
     *
     * @param LinkTypeInterface $type
     * @return Collection
     */
    public function getParents (LinkTypeInterface $type);

    /**
     * Adds
     * a
     * child
     *
     * @param LinkEntityInterface $parent            
     * @param LinkTypeInterface $type            
     */
    public function addChild (LinkEntityInterface $parent, LinkTypeInterface $type);

    /**
     * Adds
     * a
     * parent
     *
     * @param LinkEntityInterface $parent            
     * @param LinkTypeInterface $type            
     */
    public function addParent (LinkEntityInterface $parent, LinkTypeInterface $type);
}