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

use Core\Creation\AbstractSingleton;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Versioning\Entity\RepositoryInterface;

class RepositoryManager extends AbstractSingleton implements RepositoryManagerInterface, FactoryInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;
    
    protected $repositories = array();

    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        return $this;
    }
    
    protected function getUniqId(RepositoryInterface $repository){
        return get_class($repository) . '::' . $repository->getId();
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::addRepository()
     */
    public function addRepository(RepositoryInterface $repository)
    {
        if (!$this->hasRepository($repository)){
            //    throw new \Exception("There is already a repository with the identifier: " . $repository->getIdentifier());
            $uniq = $this->getUniqId($repository);      
            
            $this->getServiceLocator()->setShared('Versioning\Service\RepositoryService', false);
            $rs = $this->getServiceLocator()->get('Versioning\Service\RepositoryService');
            
            $rs->setRepository($repository);
            $rs->setIdentifier($uniq);
            $this->repositories[$uniq] = $rs;      
        }
        return $this; //->getRepository($repository);
    }

    public function hasRepository(RepositoryInterface $repository)
    {
        $uniq = $this->getUniqId($repository);
        return array_key_exists($uniq, $this->repositories);
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::removeRepository()
     */
    public function removeRepository(RepositoryInterface $repository)
    {
        if (!$this->hasRepository($repository))
            throw new \Exception("There is no repository with the identifier: " . $this->getUniqId($repository));
        
        unset($this->repositories[$this->getUniqId($repository)]);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::addRepositories()
     */
    public function addRepositories(array $repositories)
    {
        foreach ($repositories as $repository)
            $this->addRepository($repository);
        
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::getRepository()
     */
    public function getRepository(RepositoryInterface $repository)
    {
        $uniq = $this->getUniqId($repository);
        if (!$this->hasRepository($repository))
            throw new \Exception("There is no repository with the identifier: " . $uniq);
        return $this->repositories[$uniq];
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\RepositoryManagerInterface::getRepositories()
     */
    public function getRepositories()
    {
        return $this->repositories;
    }
}