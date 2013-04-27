<?php

namespace Taxonomy;

use Core\Entity\AbstractEntityAdapter;
use Core\Entity\EntityInterface;
use Taxonomy\Service\TermServiceInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Doctrine\ORM\EntityManager;

class TermManager extends AbstractEntityAdapter implements TermManagerInterface, ServiceLocatorAwareInterface {
/*
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
	}*/
	
	/**
	 * 
	 * @var array
	 */
	protected $_instances = array();
	
	/**
	 * 
	 * @var TaxonomyManagerInterface
	 */
	protected $_taxonomyManager;
	
	protected $_termEntityClassName;
	
	/**
	 * 
	 * @var ServiceLocatorInterface
	 */
	protected $_serviceLocator;
	
	/**
	 * 
	 * @var EntityManager
	 */
	protected $_entityManager;
	
	
	/**
	 * @return EntityManager $_entityManager
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

	/**
	 * @return the $_serviceLocator
	 */
	public function getServiceLocator() {
		return $this->_serviceLocator;
	}

	/**
	 * @param ServiceLocatorInterface $_serviceLocator
	 */
	public function setServiceLocator(ServiceLocatorInterface $_serviceLocator) {
		$this->_serviceLocator = $_serviceLocator;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TermManagerInterface::get()
	 */
	public function get($id) {
		return $this->_instances[$id];
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TermManagerInterface::find()
	 */
	public function find(array $path, TermServiceInterface $parent = NULL) {
		// TODO Auto-generated method stub
		$em = $this->getEntityManager();
		$qb = $em->createQueryBuilder();
		$qb->add('select', 'u')
			->add('from', 'User u')
			->add('where', 'u.id = ?1')
			->add('orderBy', 'u.name ASC');
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TermManagerInterface::create()
	 */
	public function create(array $data) {
		$term = new $this->_termEntityClassName();
		$term->populate($data);
		$ts = $this->getServiceLocator()->get('Taxonomy\Service\TermService');
		$this->add($ts);
		return $ts;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TermManagerInterface::delete()
	 */
	public function delete(TermServiceInterface $term) {
		$term->delete();
		unset($this->_instances[$term->getId()]);
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TermManagerInterface::add()
	 */
	public function add(TermServiceInterface $term) {
		$this->_instances[$term->getId()] = $term;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TermManagerInterface::setTaxonomyManager()
	 */
	public function setTaxonomyManager(TaxonomyManagerInterface $taxonomyManager) {
		$this->_taxonomyManager = $taxonomyManager;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TermManagerInterface::getTaxonomyManager()
	 */
	public function getTaxonomyManager() {
		return $this->_taxonomyManager;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TermManagerInterface::setLanguage()
	 */
	public function setLanguage(EntityInterface $language) {
		return $this->set('language', $language);
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TermManagerInterface::getLanguage()
	 */
	public function getLanguage() {
		return $this->get('language');
	}
}