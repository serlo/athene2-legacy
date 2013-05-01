<?php

namespace Taxonomy;

use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Taxonomy\Exception\NotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class SharedTaxonomyManager implements ServiceLocatorAwareInterface, SharedTaxonomyManagerInterface {
	protected $_instances = array ();
	
	/**
	 *
	 * @var EntityManager
	 */
	protected $_entityManager;
	
	protected $_serviceLocator;
	
	/**
	 *
	 * @var LanguageService
	 */
	protected $_languageService;
	
	/* (non-PHPdoc)
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	*/
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->_serviceLocator = $serviceLocator;
		return $this;
	}
	
	/* (non-PHPdoc)
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	*/
	public function getServiceLocator() {
		return $this->_serviceLocator;
	}
	
	/**
	 *
	 * @return LanguageService $_languageService
	 */
	public function getLanguageService() {
		return $this->_languageService;
	}
	
	/**
	 *
	 * @param LanguageService $_languageService        	
	 */
	public function setLanguageService(LanguageService $_languageService) {
		$this->_languageService = $_languageService;
		return $this;
	}
	
	/**
	 *
	 * @return EntityManager $_entityManager
	 */
	public function getEntityManager() {
		return $this->_entityManager;
	}
	
	/**
	 *
	 * @param EntityManager $_entityManager        	
	 */
	public function setEntityManager(EntityManager $_entityManager) {
		$this->_entityManager = $_entityManager;
		return $this;
	}
	


	/*
	 * (non-PHPdoc) @see \Taxonomy\SharedTaxonomyManagerInterface::add()
	*/
	public function add($name, TaxonomyManagerInterface $manager) {
		$this->_instances [$name] = $manager;
		return $this;
	}
	
	/*
	 * (non-PHPdoc) @see \Taxonomy\SharedTaxonomyManagerInterface::get()
	 */
	public function get($name, $languageService = NULL) {
		if (! isset ( $this->_instances [$name] )) {
			$this->add($name, $this->_find ( $name, $languageService ));
		}
		return $this->_instances [$name];
	}
	
	private function _find($name, $languageService = NULL) {
		if($languageService === NULL)
			$languageService = $this->getLanguageService();
		
		$entity = $this->getEntityManager ()->getRepository ( 'Taxonomy\Entity\Taxonomy' )->findOneBy ( array (
				'name' => $name,
				'language' => $languageService->getId () 
		) );
		
		if($entity === NULL)
			throw new NotFoundException('Taxonomy not found. Using name `'.$name.'` and language `'.$languageService->getId().'`');

		$tm = $this->getServiceLocator()->get('Taxonomy\TaxonomyManager');
		$tm->setEntity($entity);
		$tm->build();
		return $tm;
	}
	
	
}