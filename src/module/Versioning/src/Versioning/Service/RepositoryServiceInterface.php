<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Versioning\Service;

use Versioning\Entity\RevisionInterface;
use Versioning\Exception\RevisionNotFoundException;
use Doctrine\ORM\PersistentCollection;
use Versioning\Entity\RepositoryInterface;

interface RepositoryServiceInterface
{

    /**
     * Sets the identifier
     *
     * @param string $identifier            
     * @return $this
     */
    public function setIdentifier($identifier);

    /**
     * Gets the identifier
     *
     * $return string $identifier
     */
    public function getIdentifier();

    /**
     *
     * @return bool
     */
    public function hasCurrentRevision();

    /**
     * Adds a revision (makes changes persistent)
     *
     * @param RevisionInterface $revision            
     * @return $this
     */
    public function addRevision(RevisionInterface $revision);

    /**
     * Removes a revision (makes changes persistent)
     *
     * @param numeric $id            
     * @return $this
     */
    public function removeRevision($id);

    /**
     * Returns a revision
     *
     * @throws RevisionNotFoundException
     * @param numeric $id            
     * @return RevisionInterface $revision
     */
    public function getRevision($id);

    /**
     * Checks if the repository has a revision
     *
     * @param numeric $id            
     * @return bool
     */
    public function hasRevision($id);

    /**
     * Returns the revisions
     *
     * @return PersistentCollection
     */
    public function getRevisions();

    /**
     * Returns the head revision (most recent one)
     *
     * @return RevisionInterface $revision
     */
    public function getHead();

    /**
     * Checks a revision out (makes changes persistent)
     *
     * @param numeric $id            
     * @return $this
     */
    public function checkoutRevision($id);

    /**
     * Returns the revision currently set
     *
     * @return RevisionInterface
     */
    public function getCurrentRevision();

    /**
     * Sets the repository
     *
     * @param RepositoryInterface $repository            
     * @return $this
     */
    public function setRepository(RepositoryInterface $repository);

    /**
     * Gets the repository
     * 
     * @return RepositoryInterface
     */
    public function getRepository();

    /**
     * Counts the revisions in this repository
     * 
     * @return int
     */
    public function countRevisions();

    /**
     * Returns, if the repository has revisions
     * 
     * @return bool
     */
    public function hasHead();

    /**
     * Returns, if this repository has open merge requests
     * 
     * @return bool
     */
    public function isUnrevised();
}