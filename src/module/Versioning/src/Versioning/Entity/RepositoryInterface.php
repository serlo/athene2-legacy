<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Versioning\Entity;

use Core\Entity\EntityInterface;
use Doctrine\ORM\PersistentCollection;

interface RepositoryInterface extends EntityInterface
{
    /**
     * Returns the revisions
     * 
     * @return PersistentCollection
     */
    public function getRevisions ();
    
    /**
     * Adds a new revision to the repository
     * 
     * @return RevisionInterface
     */
    public function newRevision();
}