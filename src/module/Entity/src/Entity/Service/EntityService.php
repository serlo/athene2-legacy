<?php
namespace Entity\Service;

use Core\Entity\AbstractEntityAdapter;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Auth\Service\AuthServiceInterface;
use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
//use Core\Service\LanguageManagerInterface;
use Core\Service\SubjectService;
use Entity\Factory\EntityFactoryInterface;
use Versioning\RepositoryManagerInterface;
use Taxonomy\SharedTaxonomyManagerInterface;
use Core\Service\LanguageManager;
use Core\OrmEntityManagerAwareInterface;
use Link\LinkManagerInterface;

class EntityService extends AbstractEntityAdapter implements EntityServiceInterface, EventManagerAwareInterface, OrmEntityManagerAwareInterface {
    
	/**
	 * @var AuthServiceInterface
	 */
    protected $authService;

    /**
     * @var RepositoryManagerInterface
     */
    protected $repositoryManager;
    
    /**
     * @var LinkManagerInterface
     */
    protected $linkManager;
    
    /**
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     * 
     * @var SharedTaxonomyManagerInterface
     */
    protected $_sharedTaxonomyManager;

    /**
     * @var LanguageService
     */
    protected $languageService;
    
    /**
     * @var LanguageManager
     */
    protected $languageManager;
    
    /**
     * @var SubjectService
     */
    protected $subjectService;
    
    protected $events;
    
	protected $components = array();
	
	protected $entity;
	
	/**
	 * 
	 * @var \Entity\EntityManagerInterface
	 */
	protected $manager;
	
	/**
	 * @var EntityFactoryInterface
	 */
	protected $factory;

	/**
     * @return \Entity\EntityManagerInterface $manager
     */
    public function getManager ()
    {
        return $this->manager;
    }

	/**
     * @param \Entity\EntityManagerInterface $manager
     * @return $this
     */
    public function setManager (\Entity\EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        return $this;
    }

	/**
	 * @return LinkManagerInterface $linkManager
	 */
	public function getLinkManager() {
		return $this->linkManager;
	}

	/**
	 * @param LinkManagerInterface $linkManager
	 * @return $this
	 */
	public function setLinkManager(LinkManagerInterface $linkManager) {
		$this->linkManager = $linkManager;
		return $this;
	}

	/**
	 * @return LanguageManager
	 */
	public function getLanguageManager() {
		return $this->languageManager;
	}

	/**
	 * @param LanguageManager $languageManager
	 * @return $this
	 */
	public function setLanguageManager(LanguageManager $languageManager) {
		$this->languageManager = $languageManager;
		return $this;
	}

	/**
	 * @return SharedTaxonomyManagerInterface
	 */
	public function getSharedTaxonomyManager() {
		return $this->_sharedTaxonomyManager;
	}

	/**
	 * @param SharedTaxonomyManagerInterface $_sharedTaxonomyManager
	 */
	public function setSharedTaxonomyManager(SharedTaxonomyManagerInterface $_sharedTaxonomyManager) {
		$this->_sharedTaxonomyManager = $_sharedTaxonomyManager;
		return $this;
	}

	/**
	 * @return the $factory
	 */
	public function getFactory() {
		return $this->factory;
	}
	
	
	public function __call($name, $arguments){
	    return $this->factory->$name($arguments);
	}

	/**
	 * @param EntityFactoryInterface $factory
	 * @return $this
	 */
	public function setFactory(EntityFactoryInterface $factory) {
		$this->factory = $factory;
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
	 * @return RepositoryManagerInterface
	 */
	public function getRepositoryManager() {
		return $this->repositoryManager;
	}

	/**
	 * @param RepositoryManagerInterface $repositoryManager
	 */
	public function setRepositoryManager(RepositoryManagerInterface $repositoryManager) {
		$this->repositoryManager = $repositoryManager;
		return $this;
	}

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
	public function setLanguageService(LanguageService $languageService) {
		$this->languageService = $languageService;
		return $this;
	}

	/**
	 * @param SubjectService $subjectService
	 */
	public function setSubjectService(SubjectService $subjectService) {
		$this->subjectService = $subjectService;
		return $this;
	}
        
	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * @param EntityManager $entityManager
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
		return $this;
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
	
	public function addComponent($name, $component){
	    $this->components[$name] = $component;
	    return $this;
	}
	
	public function getComponent($name){
	    return $this->components[$name];
	}	

	public function getClassName($name){
	    
	}
	
	public function build() {
		// read factory class from db
		$factoryClassName = $this->getEntity()->get('factory')->get('className');
		if(substr($factoryClassName,0,1) != '\\'){
			$factoryClassName = '\\Entity\\Factory\\LearningObjects\\'.$factoryClassName;
		}
		$factory = new $factoryClassName($this);
		if(!$factory instanceof EntityFactoryInterface)
			throw new \Exception('Something somewhere went terribly wrong.');
			
		$factory->build($this);
		$this->setFactory($factory);
		
		return $factory;
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
}