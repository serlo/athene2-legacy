<?php
namespace Entity;

use Entity\Factory\EntityFactoryInterface;
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
        $entityService = $this->_createService();
		$entityService->setEntity($this->getEntityManager()->find('Entity\Entity\Entity', $id));
        $this->add($entityService);
        return $this;
    }
    
    private function _getByEntity(EntityInterface $entity){
        $entityService = $this->_createService();
		$entityService->setEntity($entity);
        $this->add($entityService);
        return $this;
    }
    
    private function _createService(){
        $sm = $this->getServiceManager();
        
        //TODO [DI] remove
        $sm->setShared('Entity\Factory\EntityFactory', false);
        $service = $sm->get('Entity\Factory\EntityFactory');
        return $service;
    }
    
    public function get($id){
    	if(is_numeric($id)){
	        if(!isset($this->_entities[$id])){
	            $this->_getById($id);
	        }
        	return $this->_entities[$id];
    	} else if ($id instanceof EntityInterface) {
	        if(!isset($this->_entities[$id->getId()])){
	            $this->_getByEntity($id);
	        }
        	return $this->_entities[$id->getId()];
    	}
    }
    
    public function add(EntityFactoryInterface $entityService){
        $entityService->setManager($this);
        $this->_entities[$entityService->getId()] = $entityService->build();
    }
}