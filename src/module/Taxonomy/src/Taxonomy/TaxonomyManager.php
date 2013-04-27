<?php

namespace Taxonomy;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Core\Entity\EntityInterface;

class TaxonomyManager implements ServiceLocatorAwareInterface, TaxonomyManagerInterface {
	/**
	 * @var ServiceLocatorInterface
	 */
	protected $_serviceManager;
	
	/**
	 * @var EntityManager
	 */
	protected $_entityManager;
	
	/**
	 * 
	 * @var array
	 */
	protected $_instances = array();
	
	/**
	 * 
	 * @var string
	 */
	protected $_taxonomyClassName = 'Taxonomy\Entity\Taxonomy';
	
	/**
	 * 
	 * @var LanguageService
	 */
	protected $_languageService;
	
	/**
	 * @return LanguageService $_languageService
	 */
	public function getLanguageService() {
		return $this->_languageService;
	}

	/**
	 * @param \Core\Service\LanguageService $_languageService
	 */
	public function setLanguageService($_languageService) {
		$this->_languageService = $_languageService;
		return $this;
	}

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
	
	
	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::find()
	*/
	public function find($type, LanguageService $languageService = NULL) {
		if($languageService === NULL && $this->getLanguageService() === NULL)
			throw new \Exception('Set a languageService first!');
		
		if($languageService === NULL)
			$languageService = $this->getLanguageService();
		
		$em = $this->getEntityManager();
		$entity = $em->getRepository($this->_taxonomyClassName)->findOneBy(array(
			'name' => $type,
			'language' => $languageService->getEntity()
		));
		
		$tm = $this->getServiceLocator()->get('Taxonomy\TermManager');
		$this->add($entity, $tm);
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::add()
	*/
	public function add(EntityInterface $entity, TermManagerInterface $termManager){
		$termManager->setEntity($entity);
		$termManager->setTaxonomyManager($this);
		$this->_instances[$entity->getId()] = $termManager;
		return $this;
	}
}