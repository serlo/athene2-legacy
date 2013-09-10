<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Versioning\Entity;

use Doctrine\ORM\PersistentCollection;

interface RepositoryInterface
{
    /**
     * @return int
     */
    public function getId();
    
    /**
     * 
     * @param int $id
     * @return $this
     */
    public function setId($id);
    
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
    
    public function getCurrentRevision();
    
    public function hasCurrentRevision();
    
    public function setCurrentRevision($revision);
}