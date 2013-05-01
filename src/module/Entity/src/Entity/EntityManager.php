<?php
namespace Entity;

use Entity\Service\EntityServiceInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager as OrmManager;
use Core\Entity\EntityInterface;

class EntityManager implements EntityManagerInterface
{    
    /**
     * 
     * @var ServiceLocatorInterface
     */
    protected $_serviceManager;
    
    /**
     * @var OrmManager
     */
    protected $_entityManager;
    
    protected $_entities;

	/**
	 * @return OrmManager
	 */
	public function getEntityManager() {
		return $this->_entityManager;
	}

	/**
	 * @param OrmManager $_entityManager
	 */
	public function setEntityManager(OrmManager $_entityManager) {
		$this->_entityManager = $_entityManager;
		return $this;
	}

	/**
	 * @return ServiceLocatorInterface $_serviceManager
	 */
	public function getServiceManager() {
		return $this->_serviceManager;
	}

	/**
	 * @param ServiceLocatorInterface $_serviceManager
	 * @return $this
	 */
	public function setServiceManager(ServiceLocatorInterface $_serviceManager) {
		$this->_serviceManager = $_serviceManager;
		return $this;
	}

	private function _getById($id){
        $sm = $this->getServiceManager();
        $entityService = $sm->get('Entity\Service\EntityService');
		$entityService->setEntity($this->getEntityManager()->find('Entity\Entity\Entity', $id));
        $this->_entities[$id] = $entityService->build();
        return $this;
    }
    
    public function _getByEntity(EntityInterface $entity){
        $sm = $this->getServiceManager();
        $entityService = $sm->get('Entity\Service\EntityService');
		$entityService->setEntity($entity);
        $this->_entities[$entity->getId()] = $entityService->build();
        return $this;
    	
    }
    
    public function get($id){
    	if(is_numeric($id)){
	        if(!isset($this->_entities[$id])){
	            $this->_getById($id);
	        }
        	return $this->_entities[$id];
    	} else if ($id instanceof EntityInterface) {
	        if(!isset($this->_entities[$id->getId()])){
	            $this->_getByEntity($id->getId());
	        }
        	return $this->_entities[$id->getId()];
    	}
    }
    
    public function add(EntityServiceInterface $entityService){
        $this->_entities[$entityService->getId()] = $entityService;
    }
}