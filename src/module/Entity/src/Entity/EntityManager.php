<?php
namespace Entity;

use Entity\Service\EntityServiceInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityManager implements EntityManagerInterface
{    
    /**
     * 
     * @var ServiceLocatorInterface
     */
    protected $_serviceManager;
    
    protected $_entities;

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
        $this->_entities[$id] = $entityService->load($id);
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