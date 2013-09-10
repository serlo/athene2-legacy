<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
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