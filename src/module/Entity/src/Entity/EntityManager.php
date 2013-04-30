<?php
namespace Entity;

use Entity\Service\EntityServiceInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager as OrmManager;

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

	private function _find($id){
        $sm = $this->getServiceManager();
        $entityService = $sm->get('Entity\Service\EntityService');
		$entityService->setEntity($this->getEntityManager()->find('Entity\Entity\Entity', $id));
        $this->_entities[$id] = $entityService->build();
        return $this;
    }
    
    public function get($id){
        if(!isset($this->_entities[$id]))
            $this->_find($id);
        
        return $this->_entities[$id];
    }
    
    public function add(EntityServiceInterface $entityService){
        $this->_entities[$entityService->getId()] = $entityService;
    }
}