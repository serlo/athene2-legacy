<?php
namespace Versioning;

use Versioning\Service\RepositoryServiceInterface;
use Versioning\Service\RepositoryService;
use Core\Creation\AbstractSingleton;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class RepositoryManager extends AbstractSingleton implements RepositoryManagerInterface, FactoryInterface
{
    private $repositories, $serviceLocator;

    public function createService (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::addRepository()
     */
    public function addRepository ($repository, $entity = NULL)
    {
        if ($repository instanceof RepositoryServiceInterface) {
            if ($this->has($repository->getIdentifier()))
                throw new \Exception("There is already a repository with the identifier: " . $repository->getIdentifier());
            $this->repositories[$repository->getIdentifier()] = $repository;
            $repository = $repository->getIdentifier();
        } else {
            if ($this->has($repository))
                throw new \Exception("There is already a repository with the identifier: " . $repository);
            $this->repositories[$repository] = new RepositoryService($repository, $entity);
        }
        return $this->getRepository($repository);
    }

    private function _hasRepository ($id)
    {
        return in_array($id, $this->$repositories);
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::removeRepository()
     */
    public function removeRepository ($repository)
    {
        if ($this->has($repository))
            throw new \Exception("There is no repository with the identifier: " . $repository);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::addRepositories()
     */
    public function addRepositories (array $repositories)
    {
        foreach($repositories as $repository)
            $this->addRepository($repository);
        
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::getRepository()
     */
    public function getRepository ($repository)
    {
        if ($this->has($repository))
            throw new \Exception("There is no repository with the identifier: " . $repository);
        return $this->repositories[$repository];
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::getRepositories()
     */
    public function getRepositories ()
    {
        return $this->repositories;
    }
}