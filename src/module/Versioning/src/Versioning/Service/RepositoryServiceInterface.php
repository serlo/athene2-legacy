<?php
namespace Versioning\Service;

use Versioning\Entity\RevisionInterface;
use Core\Entity\EntityInterface;

interface RepositoryServiceInterface
{
    /**
     * 
     * @param unknown $identifier
     * @param EntityInterface $repository     
     * @return $this
     */
    public function setup ($identifier, EntityInterface $repository);

    /**
     *
     * @param string $identifier            
     * @return $this
     */
    public function setIdentifier ($identifier);

    /**
     * $return string $identifier
     */
    public function getIdentifier ();

    /**
     *
     * @param RevisionInterface $revision            
     * @return $this
     */
    public function addRevision (RevisionInterface $revision);

    /**
     *
     * @param RevisionInterface $revision            
     * @return $this
     */
    public function deleteRevision (RevisionInterface $revision);

    /**
     *
     * @param int $revisionId            
     * @return RevisionInterface $revision
     */
    public function getRevision ($revisionId);

    /**
     *
     * @param RevisionInterface|int $revision            
     * @return bool
     */
    public function hasRevision ($revision);

    /**
     *
     * @return array
     */
    public function getRevisions ();

    /**
     *
     * @return RevisionInterface $revision
     */
    public function getHead ();

    /**
     *
     * @param RevisionInterface $revision            
     * @return $this
     */
    public function checkoutRevision (RevisionInterface $revision);

    /**
     *
     * @return RevisionInterface $revision
     */
    public function getCurrentRevision ();

    /**
     *
     * @param RevisionInterface $revision            
     * @param RevisionInterface $base            
     * @return RevisionInterface
     */
    public function mergeRevisions (RevisionInterface $revision, RevisionInterface $base);

    /**
     *
     * @param RevisionInterface $revision            
     * @return $this
     */
    public function persistRevision (RevisionInterface $revision);
}