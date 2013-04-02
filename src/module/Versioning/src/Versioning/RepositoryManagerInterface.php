<?php
namespace Versioning;

use Versioning\Service\RepositoryServiceInterface;

interface RepositoryManagerInterface
{
    /**
     * @param string|RepositoryServiceInterface $repository
     * @throws \Exception
     * @return $this
     */
    public function addRepository($repository);
    
    /**
     * @param string|RepositoryServiceInterface $repository
     * @throws \Exception
     * @return $this
     */
    public function removeRepository($repository);
    
    /**
     * @param array $repositories
     * @throws \Exception
     * @return $this
     */
    public function addRepositories(array $repositories);

    /**
     * @param string|RepositoryServiceInterface $repository
     * @throws \Exception
     * @return RepositoryInterface
     */
    public function getRepository($repository);
    
    /**
     * @return array
     */
    public function getRepositories();
}