<?php

namespace Link;

use Core\AbstractManager;
use Link\Entity\LinkEntityInterface;
use Link\Service\LinkServiceInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LinkManager extends AbstractManager implements LinkManagerInterface {

	/**
	 * @var ServiceLocatorInterface
	 */
	protected $serviceLocator;
	
	protected $options = array('instances' => array('manages' => 'Link\Service\LinkService'));
	
	
	public function __construct(){
	    parent::__construct($this->options);
	}
	
	/* (non-PHPdoc)
	 * @see \Link\LinkManagerInterface::get()
	 */
	public function get($id) {
		return $this->getInstance($id);
	}

	public function create(LinkEntityInterface $entity){
		$isntance = parent::createInstance();
		$isntance->setEntity($entity);
		$this->add($isntance);
		return $isntance;
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
}