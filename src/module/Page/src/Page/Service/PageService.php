<?php
namespace Page\Service;

use Versioning\RepositoryManagerInterface;
use Versioning\RepositoryManagerAwareInterface;
use Versioning\Service\RepositoryServiceInterface;
use Versioning\Entity\RevisionWithTitleAndContent;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Page\Entity\Page;
use Page\Entity\PageRevision;
use Auth\Service\AuthServiceInterface;
use Core\Service\LanguageService;
use Page\Entity\PageRepository;

class PageService implements PageServiceInterface, RepositoryManagerAwareInterface, ServiceLocatorAwareInterface
{

    private $entityManager;

    private $languageService, $authService;

    private $repositoryManager;

    private $serviceLocator;

    private $pages = array();

    private $slugToId = array();
    
    private $reivison;

    /**
     *
     * @return AuthServiceInterface
     */
    public function getAuthService ()
    {
        return $this->authService;
    }

    /**
     *
     * @param AuthServiceInterface $authService            
     */
    public function setAuthService (AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     *
     * @return RepositoryManagerInterface
     */
    public function getRepositoryManager ()
    {
        return $this->repositoryManager;
    }

    /**
     *
     * @param RepositoryManagerInterface $repositoryManager            
     */
    public function setRepositoryManager (RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     *
     * @return the $languageService
     */
    public function getLanguageService ()
    {
        return $this->languageService;
    }

    /**
     *
     * @param field_type $languageService            
     */
    public function setLanguageService (LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    /**
     *
     * @return the $entityManager
     */
    public function getEntityManager ()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param EntityManager $entityManager            
     */
    public function setEntityManager (EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function checkoutRevision($id, $rid, LanguageService $ls = NULL){
    	$repository = $this->getRepositoryManager()->getRepository($this->_nameRepository($id));        
    	$revision = $repository->getRevision($rid);
        $repository->checkoutRevision($revision);
    }
    
    /**
     *
     * @param int|string $id            
     * @return RepositoryServiceInterface
     */
    private function _getPage ($id)
    {
        if($id instanceof PageRepository)
            return $id;
            
        $em = $this->getEntityManager();

        if (! is_numeric($id)) {
            if (! array_key_exists($id, $this->slugToId)) {
                $pageRepo = $em->getRepository('Page\Entity\PageRepository')->findOneBy(array(
                    'slug' => $id
                ));
                if($pageRepo == NULL) throw new \Exception('Not found');
                $this->slugToId[$id] = $pageRepo->getId();
                $id = $this->slugToId[$id];
            } else {
                $id = $this->slugToId[$id];
            }
        }   

        if (! array_key_exists($id, $this->pages)) {
            $pageRepo = $em->getRepository('Page\Entity\PageRepository')->findOneBy(array(
                'page' => $id,
                'language' => $this->getLanguageService()->getId()
            ));
            $this->pages[$pageRepo->getId()] = $pageRepo;
            $this->_loadRepository($pageRepo);
        }

        return $this->pages[$id];
    }
    
    private function _loadRepository(PageRepository $pageRepo, LanguageService $ls = NULL){
        if($ls == NULL)
            $ls = $this->getLanguageService();
        
        $rm = $this->getRepositoryManager();
        $em = $this->getEntityManager();
        $name = $this->_nameRepository($pageRepo, $ls);
        $repository = $rm->addRepository($name, $pageRepo, 'Versioning\Entity\RevisionWithTitleAndContent');
    }
    
    private function _nameRepository($page, LanguageService $ls = NULL){
        if($ls === NULL)
            $ls = $this->getLanguageService();
        
        if(!$page instanceof Page)
            $page = $this->_getPage($page);

        return 'Page\Entity\PageRepository(' . $page->getId() . ', '.$ls->getId().')';
    }

    public function receive ($id)
    {
        $page = $this->_getPage($id);
        $repository = $this->getRepositoryManager()->getRepository($this->_nameRepository($id)); 
        return array(
            'page' => $page,
            'repository' => $repository
        );
    }

    public function getFieldValues ($id)
    { 
        return $this->revision->getFieldValues();
    }

    public function get ($field)
    {
    	return $this->revision->get($field);
    }
    
    public function getFieldValue ($id, $field)
    {
        return $this->get($id, $field);
    }

    public function setFieldValues (array $data)
    {
        return $this->revision->setFieldValues($data);
    }

    public function setFieldValue ($field, $value)
    {
    	return $this->set($field, $value);
    }
    
    public function set ($field, $value)
    {
        return $this->revision->set($field, $value);
    }
    
    public function prepareRevision($id, $rid = NULL){
        $repository = $this->getRepositoryManager()->getRepository($this->_nameRepository($id));
        if($rid == NULL){
            $this->revision = $repository->getCurrentRevision();
        } else {
            $this->revision = $repository->getRevision($rid);
        }
    }

    public function addRevision ($id, array $data)
    {
        $repository = $this->getRepositoryManager()->getRepository($this->_nameRepository($id));   
        
        $entity = new PageRevision();
        $entity->repository = $repository->getEntity();
        $entity->author = $this->getAuthService()->getUser();
        
        $revision = new RevisionWithTitleAndContent($entity);
        $repository->addRevision($revision);
        $repository->persistRevision($revision);
    }
    
    public function removeRevision($pageId, $revisionId){
        $repository = $this->getRepositoryManager()->getRepository($this->_nameRepository($pageId));   
             
        $revision = $repository->getRevision($revisionId);
        $repository->deleteRevision($revisionId);
    }
    
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}
	
	public function getServiceLocator() {
	    return $this->serviceLocator;		
	}

}