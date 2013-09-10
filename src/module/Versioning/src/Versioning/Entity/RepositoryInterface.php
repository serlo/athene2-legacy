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
     * 
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
     * @return PersistentCollection $revisions
     */
    public function getRevisions ();
    
    /**
     * Creates a new revision and adds it to the repository
     * 
     * @return RevisionInterface $revision
     */
    public function newRevision();
    
    /**
     * 
     * @return RevisionInterface $revision
     */
    public function getCurrentRevision();

    /**
     *
     * @return bool
     */
    public function hasCurrentRevision();

    /**
     * 
     * @param RevisionInterface $revision
     * @return $this
     */
    public function setCurrentRevision(RevisionInterface $revision);
}