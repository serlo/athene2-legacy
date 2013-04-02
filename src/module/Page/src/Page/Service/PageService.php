<?php
namespace Page\Service;

use Versioning\RepositoryManagerInterface;
use Versioning\RepositoryManagerAwareInterface;
use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Core\Service\CrudServiceInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\FactoryInterface;


class PageService implements PageServiceInterface, CrudServiceInterface, RepositoryManagerAwareInterface, FactoryInterface
{
    private $entityManager;

    private $languageService;
    
    private $repositoryManager;
    private $serviceLocator;
    
    public function createService(ServiceLocatorAwareInterface $serviceLocator){
    	$this->serviceLocator = $serviceLocator;
    	return $this;
    }

	/**
	 * @return the $repositoryManager
	 */
	public function getRepositoryManager() {
		return $this->repositoryManager;
	}

	/**
	 * @param field_type $repositoryManager
	 */
	public function setRepositoryManager(RepositoryManagerInterface $repositoryManager) {
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

    public function receive ($id)
    {
        $em = $this->getEntityManager();
        $languageId = $this->getLanguageService()->getId();
        
        $this->setCurrentRevision(
            $page = $em->getRepository('pageTranslation')->findOneBy(array(
                'page' => (int) $id,
                'language' => (int) $languageId
            ))->get('currentRevision')
        );
        
        return $this;
    }

    public function get ($field)
    {
        return $this->getEntity()->get($field);
    }

    public function set ($field, $value)
    {
        $this->getEntity()->set($field, $value);
        $this->getEntityManager()->persist($this->getEntity());
        $this->getEntityManager()->flush();
        return $this;
    }
}