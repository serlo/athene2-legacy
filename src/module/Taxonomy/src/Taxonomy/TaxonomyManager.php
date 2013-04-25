<?php

namespace Taxonomy;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Taxonomy\Service\TaxonomyServiceInterface;
use Doctrine\ORM\EntityManager;
use Core\Entity\AbstractEntity;
use Taxonomy\Exception\NotFoundException;

class TaxonomyManager implements ServiceLocatorAwareInterface {
	protected $_serviceManager;
	
	/**
	 * @var EntityManager
	 */
	protected $_entityManager;
	protected $_instances = array();
	
	protected $_termClassName = 'Taxonomy\Entity\TaxonomyTerm';
	protected $_taxonomyClassName = 'Taxonomy\Entity\Taxonomy';
	
	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->_entityManager;
	}

	/**
	 * @param EntityManager $_entityManager
	 */
	public function setEntityManager(EntityManager $_entityManager) {
		$this->_entityManager = $_entityManager;
		return $this;
	}

	public function add($id, AbstractEntity $entity){
		$service = $this->getServiceLocator()->get('Taxonomy\Service\TaxonomyService');
		$service->setEntity($entity);
		$this->_instances[$id] = $service;
		return $this;
	}
	
	public function find($id){
		$entity = $this->getEntityManager()->find($this->_termClassName, $id);
		if($entity === NULL)
			throw new NotFoundException('Not found');
		
		return $this->add($entity->getId(), $entity)->get($entity->getId());
	}	
	
	private function _findType($type){
		
	}
	
	public function findBySlugs($type, array $slugs){
		$entity = "";
		if($entity === NULL)
			throw new NotFoundException('Not found');
		return $this->add($entity->getId(), $entity)->get($entity->getId());
	}
	
	public function findByParent($slug, TaxonomyServiceInterface $parent){
		$entity = $this->getEntityManager()->getRepository($this->_termClassName)->findOneBy(array(
			'slug' => $slug,
			'taxonomy' => $parent->getEntity()->get('taxonomy'),
			'parent' => $parent->getEntity(),
		));
		
		if($entity === NULL)
			throw new NotFoundException('Not found');
		
		return $this->add($entity->getId(), $entity)->get($entity->getId());
	}
	
	
	/* (non-PHPdoc)
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->_serviceManager = $serviceLocator;
		return $this;
		
	}

	/* (non-PHPdoc)
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	 */
	public function getServiceLocator() {
		return $this->_serviceManager;
	}
}