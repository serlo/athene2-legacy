<?php
namespace Versioning;

use Versioning\Service\RepositoryServiceInterface;
use Versioning\Service\RepositoryService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class RepositoryManager implements RepositoryManagerInterface, FactoryInterface
{
    
    public static $instance;
    private $repositories, $serviceLocator;
    
    /**
     * no cloning allowed!
     */
    private function __clone(){
    }
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
	/* (non-PHPdoc)
	 * @see \Versioning\RepositoryManagerInterface::addRepository()
	 */
	public function addRepository($repository) {
	    if($repository instanceof RepositoryServiceInterface){
	        if(in_array($repository->getIdentifier(), $this->$repositories))
	            throw new \Exception("There is already a repository with the identifier: ".$repository->getIdentifier());
	        $this->repositories[$repository->getIdentifier()] = $repository;
	    } else {
	        if(in_array($repository->getIdentifier(), $this->$repositories))
	            throw new \Exception("There is already a repository with the identifier: ".$repository);
	        $this->repositories[$repository] = new RepositoryService($repository);	        
	    }
	}

	/* (non-PHPdoc)
	 * @see \Versioning\RepositoryManagerInterface::removeRepository()
	 */
	public function removeRepository($repository) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Versioning\RepositoryManagerInterface::addRepositories()
	 */
	public function addRepositories(array $repositories) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Versioning\RepositoryManagerInterface::getRepository()
	 */
	public function getRepository($repository) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Versioning\RepositoryManagerInterface::getRepositories()
	 */
	public function getRepositories() {
		// TODO Auto-generated method stub
		
	}
}