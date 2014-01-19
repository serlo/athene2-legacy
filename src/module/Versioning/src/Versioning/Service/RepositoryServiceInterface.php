<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Versioning\Service;

use User\Entity\UserInterface;
use Versioning\Entity\RepositoryInterface;

interface RepositoryServiceInterface
{
    /**
     * Gets the repository
     *
     * @return RepositoryInterface
     */
    public function getRepository();

    /**
     * Sets the repository
     *
     * @param RepositoryInterface $repository
     * @return self
     */
    public function setRepository(RepositoryInterface $repository);

    /**
     * Creates a new revision and adds it to the repository
     *
     * @param array         $data
     * @param UserInterface $user
     * @return self
     */
    public function commitRevision(array $data, UserInterface $user);

    /**
     * Sets the current revision
     *
     * @param int $id
     * @return self
     */
    public function checkoutRevision($id);

    /**
     * Finds an revision
     *
     * @param int $id
     * @return RevisionInterface
     */
    public function findRevision($id);
}