<?php
namespace Page\Service;

use Versioning\RepositoryManagerInterface;
use Versioning\RepositoryManagerAwareInterface;
use Versioning\Service\RepositoryServiceInterface;
use Versioning\Entity\RevisionWithTitleAndContent;
use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Page\Entity\Page;
use Page\Entity\PageRevision;
use Auth\Service\AuthServiceInterface;

class PageService implements PageServiceInterface, RepositoryManagerAwareInterface, ServiceLocatorAwareInterface
{

    private $entityManager;

    private $languageService, $authService;

    private $repositoryManager;

    private $serviceLocator;

    private $pages;

    private $slugToId;

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

    public function __construct ()
    {
        $this->pages = array();
        $this->slugToId = array();
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

    public function create ($data)
    {
        return $this;
    }

    public function delete ($id)
    {
        return $this;
    }

    public function update ($data)
    {
        return $this;
    }

    /**
     *
     * @param int|string $id            
     * @return RepositoryServiceInterface
     */
    private function _getPage ($id)
    {
        $em = $this->getEntityManager();
        
        if (is_string($id)) {
            if (! in_array($id, $this->slugToId)) {
                $page = $em->getRepository('Page\Entity\Page')->findOneBy(array(
                    'slug' => $id
                ));
                $this->slugToId[$id] = $page->getId();
                $id = $this->slugToId[$id];
            } else {
                $id = $this->slugToId[$id];
            }
        }
        
        if (! in_array($id, $this->pages)) {
            $page = $em->find('Page\Entity\Page', $id);
            $this->pages = array_merge(array(
                $page->getId() => $page
            ), $this->pages);
            $this->_loadRepository($page);
        }
        
        return $this->pages[$id];
    }
    
    private function _loadRepository(Page $page, LanguageService $ls = NULL){
        if($ls === NULL)
            $ls = $this->getLanguageService();
        
        $rm = $this->getRepositoryManager();
        $em = $this->getEntityManager();
        $entity = $em->getRepository('Page\Entity\PageRepository')->findOneBy(array(
            'language' => $ls->getId(),
            'page' => $page,
        ));
        $name = $this->_nameRepository($page, $ls);   
        $repository = $rm->addRepository($name, $entity);
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
        $repository = $this->getRepositoryManager()->getRepository($this->_nameRepository($id));   
        return $repository->getCurrentRevision()->getFieldValues();
    }

    public function getFieldValue ($id, $field)
    {
        $repository = $this->getRepositoryManager()->getRepository($this->_nameRepository($id));   
        return $repository->getCurrentRevision()->getFieldValue($field);
    }

    public function setFieldValues ($id, $rid, array $data)
    {
        $repository = $this->getRepositoryManager()->getRepository($this->_nameRepository($id));   
        return $repository->getRevision($rid)->setFieldValues($data);
    }

    public function setFieldValue ($id, $rid, $field, $value)
    {
        $repository = $this->getRepositoryManager()->getRepository($this->_nameRepository($id));   
        return $repository->getRevision($rid)->setFieldValue($field, $value);
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