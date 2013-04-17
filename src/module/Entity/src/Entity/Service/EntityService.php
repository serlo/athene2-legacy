<?php
/**
 * 
 * Entity requires:
 *   trashed
 *
 */

namespace Entity\Service;

use Core\Entity\AbstractEntityAdapter;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Auth\Service\AuthServiceInterface;
use Versioning\RepositoryManagerAwareInterface;
use Versioning\RepositoryManagerInterface;
use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Core\Service\SubjectService;
use Versioning\Service\RepositoryServiceInterface;
use Versioning\Entity\RevisionInterface;

class EntityService extends AbstractEntityAdapter implements EntityServiceInterface, EventManagerAwareInterface, RepositoryManagerAwareInterface {
    
	/**
	 * @var AuthServiceInterface
	 */
    protected $authService;

    /**
     * @var RepositoryManagerInterface
     */
    protected $repositoryManager;
    
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * @var LanguageService
     */
    protected $languageService;
    
    /**
     * @var SubjectService
     */
    protected $subjectService;
    
    /**
     * @var array
     */
    protected $entityPrototypes = array();
    
    /**
	 * @return the $languageService
	 */
	public function getLanguageService() {
		return $this->languageService;
	}

	/**
	 * @return the $subjectService
	 */
	public function getSubjectService() {
		return $this->subjectService;
	}

	/**
	 * @param LanguageService $languageService
	 */
	public function setLanguageService($languageService) {
		$this->languageService = $languageService;
		return $this;
	}

	/**
	 * @param SubjectService $subjectService
	 */
	public function setSubjectService($subjectService) {
		$this->subjectService = $subjectService;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Core\Entity\AbstractEntityAdapter::setEntity()
	 */
	public function setEntity($entity){
    	if(! $entity instanceOf $this->$classNamePrototypes['entity'])
    		throw new \Exception('Please use only entites that are an instance of: `'.$this->entityClassName.'`');
    	
    	$this->adaptee = $entity;
    	return $this;
    }
    
    /**
     * @return array
     */
    private function _defaultEntityPrototypes(){
    	return array(
    		'entity' => 'Entity\Entity\Entity',
    		/*'entityType' => 'Entity\Entity\EntityType',
    			
    		'component' => 'Entity\Entity\Component',
    			
    		'comment' => 'Entity\Entity\Comment',
    			
    		'license' => 'Entity\Entity\License',
    			
    		'tag' => 'Entity\Entity\Tag',
    			
    		'link' => 'Entity\Entity\Link',
    		'linkType' => 'Entity\Entity\LinkType',
    			
    		'repository' => 'Entity\Entity\Repository',
    		'revision' => 'Entity\Entity\Revision',
    		'revisionField' => 'Entity\Entity\RevisionField',
    		'revisionValue' => 'Entity\Entity\RevisionValue',*/
    	);
    }
    
    public function __construct($adaptee, array $entityPrototypes = NULL){
    	foreach($entityPrototypes as $name => $class)
    		$this->setEntityPrototype($name, $class);
    	
    	return parent::__construct($adaptee);
    }
    
    public function setEntityPrototype($name, $class){
    	if(! array_key_exists ( $name, $this->_defaultEntityPrototypes() ) )
    		throw new \Exception('The entity prototype with key `'.$name.'` is not supported by this service.');
    	
    	$this->entityPrototypes[$name] = $class;
    	return $this;
    }
    
    public function getEntityPrototype($name){
    	return $this->entityPrototypes[$name];
    }
        
	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * @param ObjectManagerAwareInterface $entityManager
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
		return $this;
	}

	public function getRepositoryManager() {
		return $this->repositoryManager;
	}

	public function setRepositoryManager(RepositoryManagerInterface $repositoryManager) {
		$this->repositoryManager = $repositoryManager;
		return $this;
	}

	public function setEventManager(EventManagerInterface $events)
    {
    	$events->setIdentifiers(array(
    			__CLASS__,
    			get_called_class(),
    	));
    	$this->events = $events;
    	return $this;
    }
    
    public function getEventManager()
    {
    	return $this->events;
    }

    /**
     * @return AuthServiceInterface
     */
    public function getAuthService() {
    	return $this->authService;
    }
    
    /**
     * @param AuthServiceInterface $authService
     */
    public function setAuthService(AuthServiceInterface $authService) {
    	$this->authService = $authService;
		return $this;
    }

	public function kill() {
		$this->set('killed',true);
		$this->persist();
		return $this;
	}
	
	public function persist(){
		$em = $this->getEntityManager();
		$e = $this->getEntity();
		$em->persist($e);
		$em->flush();
		return $this;
	}

	public function load($id) {
		$this->setEntity($this->getEntityManager()->find($this->getEntityPrototype('entity'), $id));
		return $this;
	}
	
	public function create(array $data) {
		$entity = new $this->entityClassName();
		$this->setEntity($entity);
		$entity->populate($data);
	}
	
	/**
	 * Private method for returning the repository for this Entity.
	 * 
	 * @param string $field
	 * @return RepositoryServiceInterface
	 */
	private function _getRepository(){
		return $this->getRepositoryManager()->getRepository($this->_nameRepository());
	}
	
	private function _setRepository(){
		
	}
	
	/**
	 * Method for returning a revision.
	 * Leave $id empty for the current revision.
	 * 
	 * @param string $id
	 * @return RevisionInterface
	 */
	public function getRevision($id = NULL){
		if($id === NULL){
			return $this->_getRepository()->getCurrentRevision();
		} else {
			return $this->_getRepository()->getRevision($id);
		}
	}
	
	/**
	 * Private method for giving the repository a unique ID
	 * 
	 * @return string
	 */
	private function _nameRepository(){
		return 'Entity\Service\EntityService('.$this->getEntity()->getId().')';
	}		
}