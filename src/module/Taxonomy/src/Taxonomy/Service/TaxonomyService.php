<?php
namespace Taxonomy\Service;

use Core\Entity\AbstractEntityAdapter;
use Core\Entity\AbstractEntity;
use Doctrine\ORM\EntityManager;
use Taxonomy\TaxonomyManagerInterface;
use Taxonomy\Factory\FactoryInterface;
use Taxonomy\Exception\BadTypeException;
use Taxonomy\Exception\NotSetException;

class TaxonomyService extends AbstractEntityAdapter {

	/**
	 * @var EntityManager
	 */
	protected $_entityManager;
	
	/**
	 * @var TaxonomyManagerInterface
	 */
	protected $_taxonomyManager;
	
	/**
	 * @var FactoryInterface
	 */
	protected $_factory;
	
	protected $_associations = array();
	
	/**
	 * @return the $_termClassName
	 */
	public function getTermClassName() {
		if(!$this->_termClassName)
			throw new NotSetException('Set termClassName first.');
		
		return $this->_termClassName;
	}

	public function setEntity(AbstractEntity $entity){
		parent::setEntity($entity);
	}
	
	/**
	 * @return the $factory
	 */
	public function getFactory() {
		return $this->_factory;
	}

	/**
	 * @param FactoryInterface $factory
	 */
	public function setFactory(FactoryInterface $factory) {
		$this->_factory = $factory;
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
	 */
	public function setTaxonomyManager(TaxonomyManagerInterface $_taxonomyManager) {
		$this->_taxonomyManager = $_taxonomyManager;
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
	
	public function prepare(){
		$className = $this->getEntity()->get('taxonomy')->get('factory')->get('class_name');
		
		// read factory class from db
		if(substr($className,0,1) != '\\'){
			$factoryClassName = '\\Taxonomy\\Factory\\'.$factoryClassName;
		}
		
		$factory = new $className();
		if(!$factory instanceof FactoryInterface)
			throw new BadTypeException('Something somewhere went terribly wrong.');
			
		$factory->build($this);
		$this->setFactory($factory);
		
		return $this;
	}
		
	
	public function persist(AbstractEntity $entity = NULL){
		if($entity === NULL){
			$entity = $this->getEntity();
		}
		$em = $this->getEntityManager();
		$em->persist($entity);
		$em->flush($entity);
		return $this;
	}
	
	public function addAssociation($destination){
		if(!in_array($destination, $this->_associations))
			$this->_associations[] = $destination;
		
		return $this;
	}
	
	public function getAssociated($destination){
		if(!in_array($destination, $this->_associations))
			throw new NotSetException('Association '.$destination.' does not exist. Did you use singular instead of plural?');
		
		return $this->getTerm()->get($destination);
	}
	
	public function getTerm(){
		if(!$this->_term)
			throw new NotSetException('Use `useTerm()` first!');
		return $this->_term;
	}
	
	public function addTerm($name, $slug){
		$term = new $this->classNames['taxonomy'];
		$term->set('name', $name)
			 ->set('slug', $slug)
			 ->set('parent', $this->getTerm());
		$this->persist($term);
		$this->getEntity()->add($term);
		return $this;
	}
	
	public function useParentTerm(){
		return $this->getTerm()->get('parent');
	}
	
	public function getChildTerms($type = NULL){
		return $this->getTerm()->get('children');
	}
}