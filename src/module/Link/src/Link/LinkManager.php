<?php

namespace Link;

use Core\AbstractManager;
use Link\Entity\LinkEntityInterface;
use Link\Service\LinkServiceInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LinkManager extends AbstractManager implements LinkManagerInterface, ServiceLocatorAwareInterface {

	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;
	
	/* (non-PHPdoc)
	 * @see \Link\LinkManagerInterface::get()
	 */
	public function get($id) {
		return $this->getInstance($id);
	}

	public function create(LinkEntityInterface $entity){
		
		$sl = $this->getServiceLocator();
		
		// TODO !dirty !di Remove
		$sl->setShared('Link\Service\LinkService',false);
		$ls = $this->getServiceLocator()->get('Link\Service\LinkService');
		$ls->setEntity($entity);
		$this->add($ls);
		
		return $ls;
	}
	
	/* (non-PHPdoc)
	 * @see \Link\LinkManagerInterface::add()
	 */
	public function add(LinkServiceInterface $linkService) {
		$this->addInstance($linkService->getId(), $linkService);
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Link\LinkManagerInterface::has()
	 */
	public function has($name) {
		return $this->hasInstance($name);
	}
	
	/* (non-PHPdoc)
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
}