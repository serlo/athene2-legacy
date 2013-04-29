<?php

namespace Taxonomy;

use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Taxonomy\Exception\NotFoundException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class SharedTaxonomyManager implements ServiceLocatorAwareInterface, SharedTaxonomyManagerInterface {
	protected $_instances = array ();
	
	/**
	 *
	 * @var EntityManager
	 */
	protected $_entityManager;
	
	/**
	 *
	 * @var LanguageService
	 */
	protected $_languageService;
	
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
	 * (non-PHPdoc) @see \Taxonomy\SharedTaxonomyManagerInterface::get()
	 */
	public function get($name) {
		if (! isset ( $this->_instances [$name] )) {
			$this->add ( $name, $this->_find ( $name ) );
		}
		return $this->_instances [$name];
	}
	private function _find($name) {
		$language = $this->getLanguageService();
		$entity = $this->getEntityManager ()->getRepository ( 'Taxonomy\Entity\Taxonomy' )->findOneBy ( array (
				'name' => $name,
				'language' => $language->getEntity () 
		) );
		
		if($entity === NULL)
			throw new NotFoundException('Taxonomy not found. Using name `'.$name.'` and language `'.$language->getId().'`');
		
		$this->add($name);
	}
	
	/*
	 * (non-PHPdoc) @see \Taxonomy\SharedTaxonomyManagerInterface::add()
	 */
	public function add($name, TaxonomyManagerInterface $manager) {
		$this->_instances [$name] = $manager;
		return $this;
	}
}