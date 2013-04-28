<?php

namespace Taxonomy\Service;

use Core\Entity\AbstractEntityAdapter;
use Taxonomy\TermManagerInterface;
use Taxonomy\Exception\BadTypeException;
use Taxonomy\Factory\FactoryInterface;
use Taxonomy\Exception\LinkNotAllowedException;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Taxonomy\TaxonomyManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TermService extends AbstractEntityAdapter implements TermServiceInterface, ServiceLocatorAwareInterface {
	/**
	 * @var TermManagerInterface
	 */
	protected $_taxonomyManager;
	
	protected $_serviceLocator;
	
	/**
	 * @var EntityManager
	 */
	protected $_entityManager;
	
	protected $_allowedLinkList = array();
	

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
	 * @see \Taxonomy\Service\TermServiceInterface::setTaxonomyManager()
	 */
	public function setTaxonomyManager(TaxonomyManagerInterface $_taxonomyManager) {
		$this->_taxonomyManager = $_taxonomyManager;
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::getTaxonomyManager()
	 */
	public function getTaxonomyManager() {
		return $this->_taxonomyManager;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::build()
	 */
	public function build() {
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

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::allowLink()
	 */
	public function allowLink($link, $callback = NULL) {
		if($callback === NULL){
			$this->_allowedLinkList[$link] = FALSE;
		} else {
			$this->_allowedLinkList[$link] = $callback;			
		}
		return $this;
	}

	private function _isLinkingAllowed($to){
		if(!array_key_exists($to, $this->_allowedLinkList))
			throw new LinkNotAllowedException('Linking of `'.$to.'` not allowed.');
	}
	
	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::getLinks()
	 */
	public function getLinks($link) {
		$this->_isLinkingAllowed($link);
		
		$entities = $this->getEntity()->get($link);
		if($this->_allowedLinkList[$link]){
			$entities = array();
			$callback = $this->_allowedLinkList[$link];
			$entityArray = $entities->toArray();
			foreach($entityArray as $entity){
				$callbackReturn = $callback($entity, $this->getServiceLocator());
				if(!is_object($callbackReturn))
					throw new \Exception('Fatal: Callback returned no instance');
				
				$entities[] = $callbackReturn;
			}
		}
		return $entities;
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::addLink()
	 */
	public function addLink($link, AbstractEntityAdapter $entity) {
		$this->_isLinkingAllowed($link);
		
		$this->getEntity()->add($entity->getEntity());
		return $this->persist();
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::removeLink()
	 */
	public function removeLink($link, AbstractEntityAdapter $entity) {
		$this->_isLinkingAllowed($link);
		
		$this->getEntity()->remove($entity->getEntity());
		return $this->persist();		
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::persist()
	 */
	public function persist($entity = NULL) {
		if($entity === NULL){
			$entity = $this->getEntity();
		}
		$em = $this->getEntityManager();
		$em->persist($entity);
		$em->flush();
		return $this;
	}
	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::getParent()
	 */
	public function getParent() {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::setParent()
	 */
	public function setParent(\Taxonomy\Service\TermServiceInterface $term) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::getChildren()
	 */
	public function getChildren() {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Taxonomy\Service\TermServiceInterface::addChild()
	 */
	public function addChild(\Taxonomy\Service\TermServiceInterface $term) {
		// TODO Auto-generated method stub
		
	}
}