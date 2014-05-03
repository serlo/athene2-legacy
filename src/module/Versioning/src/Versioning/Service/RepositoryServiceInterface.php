<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Versioning\Service;

use Versioning\Entity\RepositoryInterface;
use Versioning\Entity\RevisionInterface;

interface RepositoryServiceInterface
{
    /**
     * @param int|RevisionInterface $revision
     * @param string                $reason
     *
     * @return mixed
     */
    public function checkoutRevision($revision, $reason = '');

    /**
     * Creates a new revision and adds it to the repository
     *
     * @param array $data
     *
     * @return RevisionInterface
     */
    public function commitRevision(array $data);

    /**
     * Finds an revision
     *
     * @param int|RevisionInterface $id
     *
     * @return RevisionInterface
     */
    public function findRevision($id);

    /**
     * Gets the repository
     *
     * @return RepositoryInterface
     */
    public function getRepository();

    /**
     * @param int|RevisionInterface $revision
     * @param string                $reason
     *
     * @return void
     */
    public function rejectRevision($revision, $reason = '');

    /**
     * Sets the repository
     *
     * @param RepositoryInterface $repository
     *
     * @return void
     */
    public function setRepository(RepositoryInterface $repository);
}
