<?php

namespace Taxonomy\Service;

use Zend\View\Model\ViewModel;
use Taxonomy\TaxonomyManagerInterface;
use Core\Entity\EntityInterface;
use Taxonomy\Exception\LinkNotAllowedException;
use Core\Entity\AbstractEntityAdapter;
use Doctrine\ORM\EntityManager;

class TermService extends AbstractEntityAdapter implements TermServiceInterface {
	/**
	 * 
	 * @var TaxonomyManagerInterface
	 */
	protected $_taxonomyManager;
	
	/**
	 * 
	 * @var EntityManager
	 */
	protected $_entityManager;
	
	protected $_template;
	
	protected $_allowedLinks;
	
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

	/**
	 * @return TaxonomyManagerInterface
	 */
	public function getTaxonomyManager() {
		return $this->_taxonomyManager;
	}

	/**
	 * @param TaxonomyManagerInterface $_taxonomyManager
	 * @return $this
	 */
	public function setTaxonomyManager(TaxonomyManagerInterface $_taxonomyManager) {
		$this->_taxonomyManager = $_taxonomyManager;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::setTemplate()
	 */
	public function setTemplate($template) {
		$this->_template = $template;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::getViewModel()
	 */
	public function getViewModel() {
		$view = new ViewModel(array(
				
		));
		$view->setTemplate($this->_template);
		return $view;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::getParent()
	 */
	public function getParent() {
		return $this->getTaxonomyManager()->getTerm($this->getEntity()->get('parent'));
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::getChildren()
	 */
	public function getChildren() {
		return $this->getTaxonomyManager()->getTerms($this->getEntity()->get('children'));
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::toArray()
	 */
	public function toArray() {
		return $this->getEntity()->toArray();
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::getAllLinks()
	 */
	public function getAllLinks() {
		$return = array();
		foreach($this->_allowedLinks as $targetField => $callback){
			$return[$targetField] = $this->getLinks($targetField);
		}
		return $return;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::getLinks()
	 */
	public function getLinks($targetField) {
		$this->_linkingAllowedWithException($targetField);
		$services = array();
		foreach($this->get($targetField)->toArray() as $entity){
			$service[] = $this->_allowedLinks[$targetField]($entity);
		}
		return $service;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::addLink()
	 */
	public function addLink($targetField, EntityInterface $target) {
		$this->_linkingAllowedWithException($targetField);
		$entity = $this->getEntity();
		$entity->get($targetField)->add($target);
		$this->persist();
		return $this;	
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::removeLink()
	 */
	public function removeLink($targetField, EntityInterface $target) {
		$this->_linkingAllowedWithException($targetField);
		$entity = $this->getEntity();
		$entity->get($targetField)->remove($target);
		$this->persist();
		return $this;	
	}
	
	public function hasLink($targetField, EntityInterface $target){
		$this->_linkingAllowedWithException($targetField);
		$entity = $this->getEntity();
		return $entity->get($targetField)->containsKey($target->getId());
	}
	
	public function linkingAllowed($targetField){
		return $this->getTaxonomyManager()->linkingAllowed($targetField);
	}
	
	protected function _linkingAllowedWithException($targetField){
		if(!$this->linkingAllowed($targetField))
			throw new LinkNotAllowedException();
	}

	public function persist(){
		$em = $this->getEntityManager();
		$em->persist($this->getEntity());
		$em->flush();
	}
}