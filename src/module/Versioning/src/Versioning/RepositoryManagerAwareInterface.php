<?php
namespace Versioning;

interface RepositoryManagerAwareInterface
{
    /**
     * Set repository manager
     *
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager);
    

    /**
     * @return RepositoryManagerInterface
     */
    public function getRepositoryManager();
}