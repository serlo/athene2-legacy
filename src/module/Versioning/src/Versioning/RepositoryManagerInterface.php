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
namespace Versioning;

use Versioning\Service\RepositoryServiceInterface;
use Versioning\Entity\RepositoryInterface;

interface RepositoryManagerInterface
{

    /**
     *
     * @param string|RepositoryServiceInterface $repository
     * @return RepositoryServiceInterface
     */
    public function addRepository(RepositoryInterface $repository);

    /**
     *
     * @param RepositoryServiceInterface $repository
     * @return $this
     */
    public function removeRepository(RepositoryInterface $repository);

    /**
     *
     * @param array $repositories
     * @return $this
     */
    public function addRepositories(array $repositories);

    /**
     *
     * @param string $repository
     * @return RepositoryInterface
     */
    public function getRepository(RepositoryInterface $repository);

    /**
     * Returns all repositories
     *
     * @return array
     */
    public function getRepositories();
    
    /**
     * 
     * @param RepositoryInterface $repository
     * @return bool
     */
    public function hasRepository(RepositoryInterface $repository);
}