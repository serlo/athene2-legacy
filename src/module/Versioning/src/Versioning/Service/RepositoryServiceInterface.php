<?php
namespace Versioning\Service;

use Versioning\Entity\RevisionInterface;
use Core\Entity\EntityInterface;
use Versioning\Exception\RevisionNotFoundException;
use Doctrine\ORM\PersistentCollection;

interface RepositoryServiceInterface
{
    /**
     * Setups some stuff 
     * 
     * @param unknown $identifier
     * @param EntityInterface $repository     
     * @return $this
     */
    public function setup ($identifier, EntityInterface $repository);

    /**
     * Sets the identifier
     *
     * @param string $identifier            
     * @return $this
     */
    public function setIdentifier ($identifier);

    /**
     * Gets the identifier
     * 
     * $return string $identifier
     */
    public function getIdentifier ();
    
    public function hasCurrentRevision();

    /**
     * Adds a revision (makes changes persistent)
     *
     * @param RevisionInterface $revision            
     * @return $this
     */
    public function addRevision (RevisionInterface $revision);

    /**
     * Removes a revision (makes changes persistent)
     *
     * @param RevisionInterface $revision            
     * @return $this
     */
    public function removeRevision (RevisionInterface $revision);

    /**
     * Returns a revision
     *
     * @throws RevisionNotFoundException
     * @param int $revisionId            
     * @return RevisionInterface $revision
     */
    public function getRevision ($revisionId);

    /**
     * Checks if the repository has a revision
     *
     * @param RevisionInterface|int $revision            
     * @return bool
     */
    public function hasRevision ($revision);

    /**
     * Returns the revisions
     *
     * @return PersistentCollection
     */
    public function getRevisions ();

    /**
     * Returns the head revision (most recent one)
     *
     * @return RevisionInterface $revision
     */
    public function getHead ();

    /**
     * Checks a revision out (makes changes persistent)
     *
     * @param RevisionInterface $revision            
     * @return $this
     */
    public function checkoutRevision (RevisionInterface $revision);

    /**
     * Returns the revision currently set
     *
     * @return RevisionInterface $revision
     */
    public function getCurrentRevision ();

    /**
     * Merges two revisions
     *
     * @param RevisionInterface $revision            
     * @param RevisionInterface $base            
     * @return RevisionInterface
     */
    public function mergeRevisions (RevisionInterface $revision, RevisionInterface $base);

    /**
     * Makes changes on the revision persistent
     *
     * @param RevisionInterface $revision            
     * @return $this
     */
    public function persistRevision (RevisionInterface $revision);
}