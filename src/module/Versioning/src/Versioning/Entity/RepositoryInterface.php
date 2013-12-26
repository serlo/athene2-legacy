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
     * Returns the revisions
     *
     * @return PersistentCollection
     */
    public function getRevisions();

    /**
     * Creates a new revision
     *
     * @return RevisionInterface
     */
    public function createRevision();

    /**
     *
     * @return RevisionInterface
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
     * @return self
     */
    public function setCurrentRevision(RevisionInterface $revision);

    /**
     *
     * @param RevisionInterface $revision            
     * @return self
     */
    public function addRevision(RevisionInterface $revision);

    /**
     *
     * @param RevisionInterface $revision            
     * @return self
     */
    public function removeRevision(RevisionInterface $revision);
}