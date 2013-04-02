<?php
namespace Versioning;

interface RepositoryManagerAwareInterface
{
    /**
     * Set repository manager
     *
     * @param RepositoryManager $repositoryManager
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager);
}