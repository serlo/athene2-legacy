<?php
namespace Taxonomy;

use module\Taxonomy\src\Taxonomy\Service\TermServiceInterface;
use Taxonomy\Exception\BadTypeException;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Criteria;
use Core\Entity\AbstractEntityAdapter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\Common\Collections\Collection;
use Core\Entity\EntityInterface;

class TaxonomyManager extends AbstractEntityAdapter implements TaxonomyManagerInterface, ServiceLocatorAwareInterface {

	/**
	 * @var EntityManager
	 */
	protected $_entityManager;
	
	/**
	 * @var ServiceLocatorInterface
	 */
	protected $_serviceLocator;
	
	protected $_terms = array();
	protected $_template;
	protected $_termTemplate;


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
	 * @return EntityManager $_entityManager
	 */
	public function getEntityManager() {
		return $this->_entityManager;
	}

	/**
	 * @param EntityManager $_entityManager
	 * @return $this
	 */
	public function setEntityManager(EntityManager $_entityManager) {
		$this->_entityManager = $_entityManager;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::addTerm()
	 */
	public function addTerm(TermServiceInterface $ts) {
		$this->_terms[$ts->getId()] = $ts;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::createTerm()
	 */
	public function createTerm() {
		// TODO Auto-generated method stub
		$ts = '';
		$this->addTerm($ts);
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::hasTerm()
	 */
	public function hasTerm($val) {
		// TODO do me 
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::getTerm()
	 */
	public function getTerm($val) {
		if(is_numeric($val)){
			$return = $this->_getTermById($val);
		} else if (is_array($val)) {
			$return = $this->_getTermByPath($val);
		} else if ($val instanceof EntityInterface) {
			$return = $this->_getTermByEntity($val);
		} else {
			throw new BadTypeException();
		}
		return $return;
	}
	
	protected function _getTermByEntity(EntityInterface $entity){
		$id = $entity->getId();
		if(isset($this->_terms[$id])){
			return $this->_terms[$id];
		}
		$service = $this->_entityToService($entity);
		$this->addTerm($service);
		return $service;
	}	
	
	protected function _getTermById($id){
		if(isset($this->_terms[$id])){
			return $this->_terms[$id];
		}
		$service = $this->_entityToService($this->get('terms')->get($id));
		$this->addTerm($service);
		return $service;
	}
	
	protected function _getTermByPath(array $path){
		
	}
	
	protected function _entitiesToServices(Collection $entities){
		$return = array();
		foreach($entities->toArray() as $entity){
			$return[] = $this->_entityToService($entity);	
		}
		return $return;
	}
	
	protected function _entityToService($entity){
		$ts = $this->getServiceLocator()->get('Taxonomy\Service\TermService');
		$ts->setEntity($entity);
		return $ts;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::getTerms()
	 */
	public function getTerms(Criteria $filter = NULL) {
		return $this->get('terms')->matching($filter);
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::toArray()
	 */
	public function toArray() {
		$this->getEntity()->toArray();
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::setTemplate()
	 */
	public function setTemplate($template) {
		$this->_template = $template;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\TaxonomyManagerInterface::setTermTemplate()
	 */
	public function setTermTemplate($template) {
		$this->_termTemplate = $template;
		return $this;
	}
}