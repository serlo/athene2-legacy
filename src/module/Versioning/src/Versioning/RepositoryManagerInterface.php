<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Versioning;

use Versioning\Service\RepositoryServiceInterface;
use Versioning\Entity\RepositoryInterface;

interface RepositoryManagerInterface
{

    /**
     *
     * @param string|RepositoryServiceInterface $repository            
     * @throws \Exception
     * @return RepositoryServiceInterface
     */
    public function addRepository(RepositoryInterface $repository);

    /**
     *
     * @param string|RepositoryServiceInterface $repository            
     * @throws \Exception
     * @return $this
     */
    public function removeRepository(RepositoryInterface $repository);

    /**
     *
     * @param array $repositories            
     * @throws \Exception
     * @return $this
     */
    public function addRepositories(array $repositories);

    /**
     *
     * @param string $repository            
     * @throws \Exception
     * @return RepositoryInterface
     */
    public function getRepository(RepositoryInterface $repository);

    /**
     * Returns all registered repositories
     *
     * @return array
     */
    public function getRepositories();
}