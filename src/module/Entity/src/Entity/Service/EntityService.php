<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Service;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Taxonomy\SharedTaxonomyManagerAwareInterface;
use Taxonomy\SharedTaxonomyManagerInterface;
use Core\Service\LanguageManager;
use Link\LinkManagerInterface;
use Zend\EventManager\EventManagerInterface;
use Entity\Factory\EntityBuilderInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Auth\Service\AuthServiceInterface;
use Core\Service\LanguageService;
use Versioning\RepositoryManagerInterface;
use Core\Service\SubjectService;
use Core\Service\AbstractEntityDecorator;
use Core\Entity\EntityInterface;

class EntityService extends AbstractEntityDecorator implements EntityServiceInterface, ObjectManagerAwareInterface, SharedTaxonomyManagerAwareInterface, EventManagerAwareInterface
{
    
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
	
	protected $_factoryClassName;
	
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
    
    public function getFactoryClassName(){
        return $this->_factoryClassName;
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
	/**
	 * @param EntityBuilderInterface $factory
	 * @return $this
	 */
	public function setFactory(EntityBuilderInterface $factory) {
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
    
    public function build(){
        if(is_object($this->getFactory())) 
            throw new \Exception('This Service already has been build.');       
        
        $className = $this->getEntity()->getFactory()->getName();
		$fullFactoryClassName = $className;
		
		if(!class_exists($fullFactoryClassName))
			throw new \Exception('Class: ´'.$fullFactoryClassName.'´ not found');
		
		$factory = new $fullFactoryClassName();
		return $factory->build($this);
    }
}