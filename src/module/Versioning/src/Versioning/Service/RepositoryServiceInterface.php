<?php
namespace Versioning\Service;

use Versioning\Entity\RevisionInterface;
use Versioning\Entity\RepositoryInterface;
use Core\Entity\AbstractEntityAdapter;

interface RepositoryServiceInterface
{
    
    /**
     * @param string $identifier
     * @return $this
     */
    public function __construct($identifier, RepositoryInterface $repository);

    /**
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier);

    /**
     * $return string $identifier
     */
    public function getIdentifier();
    
    /**
     * @param RevisionInterface $prototype
     * @return $this
     */
    public function setPrototype(RevisionInterface $prototype);
    
    /**
     * 
     * @param RevisionInterface $revision
     * @return $this
     */
    public function addRevision(RevisionInterface $revision);
    
    /**
     * 
     * @param RevisionInterface $revision
     * @return $this
     */
    public function deleteRevision(RevisionInterface $revision);
    
    /**
     *
     * @param RevisionInterface $revision
     * @return $this
     */
    public function trashRevision(RevisionInterface $revision);
    
    /**
     * @param int $revisionId
     * @return RevisionInterface $revision
     */
    public function getRevision($revisionId);
    
    /**
     * @param RevisionInterface $revision
     * @return bool
     */
    public function hasRevision(RevisionInterface $revision);
        
    /**
     * @return array
     */
    public function getTrashedRevisions();
    
    /**
     * @return array
     */
    public function getRevisions();

    /**
     * @return RevisionInterface $revision
     */
    public function getHead();
    
    /**
     * @param RevisionInterface $revision
     * @return $this
     */
    public function checkoutRevision(RevisionInterface $revision);

    /**
     * @return RevisionInterface $revision
     */
    public function getCurrentRevision();
    
    /**
     * @param RevisionInterface $revision
     * @param RevisionInterface $base
     * @return RevisionInterface
     */
    public function mergeRevisions(RevisionInterface $revision, RevisionInterface $base);
    
    /**
     * 
     * @param RevisionInterface $revision
     * @return $this
     */
    public function persistRevision(AbstractEntityAdapter $revision);
}