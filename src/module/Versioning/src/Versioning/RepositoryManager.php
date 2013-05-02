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
use Versioning\Service\RepositoryService;
use Core\Creation\AbstractSingleton;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Versioning\Entity\Repository;

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
            if ($this->_hasRepository($repository->getIdentifier()))
                throw new \Exception("There is already a repository with the identifier: " . $repository->getIdentifier());
            $this->repositories[$repository->getIdentifier()] = $repository;
            $repository = $repository->getIdentifier();
        } else {
            if ($this->_hasRepository($repository))
                throw new \Exception("There is already a repository with the identifier: " . $repository);
            $rs = $this->serviceLocator->get('Versioning\Service\RepositoryService');
            $rs->setup($repository, $entity);
            $this->repositories[$repository] = $rs; // new RepositoryService();
        }
        return $this->getRepository($repository);
    }

    private function _hasRepository ($id)
    {
        return ($this->repositories !== NULL) ? in_array($id, $this->repositories) : FALSE;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::removeRepository()
     */
    public function removeRepository ($repository)
    {
        if ($this->_hasRepository($repository))
            throw new \Exception("There is no repository with the identifier: " . $repository);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::addRepositories()
     */
    public function addRepositories (array $repositories)
    {
        foreach ($repositories as $repository)
            $this->addRepository($repository);
        
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::getRepository()
     */
    public function getRepository ($repository)
    {
        if ($this->_hasRepository($repository))
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