<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace Link\Entity;

use Core\Entity\EntityInterface;
use Doctrine\Common\Collections\Collection;

interface LinkEntityInterface extends EntityInterface
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
}