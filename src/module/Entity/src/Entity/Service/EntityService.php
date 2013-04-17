<?php
/**
 * 
 * Entity requires:
 *   trashed
 *
 */

namespace Entity\Service;

use Core\Entity\AbstractEntityAdapter;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Auth\Service\AuthServiceInterface;
use Versioning\RepositoryManagerAwareInterface;
use Versioning\RepositoryManagerInterface;
use Doctrine\ORM\EntityManager;

class EntityService extends AbstractEntityAdapter implements EntityServiceInterface, EventManagerAwareInterface, RepositoryManagerAwareInterface {
    
    protected $authService;
    protected $repositoryManager;
    protected $entityManager;
    protected $events;
    
    protected $entityClassName;
    
    public function setEntity($entity){
    	if(! $entity instanceOf $this->entityClassName)
    		throw new \Exception('Please use only entites that are an instance of: `'.$this->entityClassName.'`');
    	
    	$this->adaptee = $entity;
    	return $this;
    }
    
    public function __construct($adaptee, $entityClassName = '\Entity\Entity\Entity'){
    	$this->entityClassName = $entityClassName;
    	return parent::__construct($adaptee);
    }
        
	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * @param ObjectManagerAwareInterface $entityManager
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
		return $this;
	}

	public function getRepositoryManager() {
		return $this->repositoryManager;
	}

	public function setRepositoryManager(RepositoryManagerInterface $repositoryManager) {
		$this->repositoryManager = $repositoryManager;
		return $this;
	}

	public function setEventManager(EventManagerInterface $events)
    {
    	$events->setIdentifiers(array(
    			__CLASS__,
    			get_called_class(),
    	));
    	$this->events = $events;
    	return $this;
    }
    
    public function getEventManager()
    {
    	return $this->events;
    }

    /**
     * @return AuthServiceInterface
     */
    public function getAuthService() {
    	return $this->authService;
    }
    
    /**
     * @param AuthServiceInterface $authService
     */
    public function setAuthService(AuthServiceInterface $authService) {
    	$this->authService = $authService;
		return $this;
    }

	public function kill() {
		$this->set('killed',true);
		$this->persist();
		return $this;
	}
	
	public function persist(){
		$em = $this->getEntityManager();
		$e = $this->getEntity();
		$em->persist($e);
		$em->flush();
		return $this;
	}

	public function receive($id) {
		$this->setEntity($this->getEntityManager()->find($this->entityClassName, $id));
		return $this;
	}
	
	public function create(array $data) {
		$entity = new $this->entityClassName();
		$this->setEntity($entity);
		$entity->populate($data);
	}
}