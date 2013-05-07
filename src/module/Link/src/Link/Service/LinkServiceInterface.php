<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace Link\Service;

use Doctrine\Common\Collections\Collection;

interface LinkServiceInterface
{

    /**
     * Returns the children
     *
     * @return Collection
     */
    public function getChildren ();

    /**
     * Returns the parents
     *
     * @return Collection
     */
    public function getParents ();

    /**
     * Adds a parent
     *
     * @param LinkServiceInterface $child            
     * @return $this
     */
    public function addParent (LinkServiceInterface $parent);

    /**
     * Adds a child
     *
     * @param LinkServiceInterface $child            
     * @return $this
     */
    public function addChild (LinkServiceInterface $child);
}