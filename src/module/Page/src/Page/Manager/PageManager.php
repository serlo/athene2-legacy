<?php
namespace Page\Manager;

use Page\Manager\PageManagerInterface;
use Page\Entity\Page;
use Page\Entity\PageRepositoryInterface;
use  Page\Exception\PageNotFoundException;
use Page\Exception\InvalidArgumentException;
use Page\Service\PageServiceInterface;
class PageManager implements PageManagerInterface
{
    
    use \Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait,\Language\Service\LanguageServiceAwareTrait,\Common\Traits\EntityDelegatorTrait;
    use \Page\Manager\PageManagerAwareTrait;
    use \Uuid\Manager\UuidManagerAwareTrait;
    use \Language\Manager\LanguageManagerAwareTrait;
    use \User\Manager\UserManagerAwareTrait;
    
    /*
     * (non-PHPdoc) @see \Page\Manager\PageManagerInterface::getPageRepository()
     */
    public function getRevision($id)
    {
              return $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Page\Entity\PageRevisionInterface'), $id);
    }
    
    public function getPageRepository($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $pageRepository = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Page\Entity\PageRepository'), $id);
            if (! $pageRepository)
                throw new PageNotFoundException(sprintf('PageRepository %s not found', $id));
            
            $instance = $this->createService($pageRepository);
            $this->addInstance($pageRepository->getId(), $instance);
            return $instance;
        }
        
        return $this->getInstance($id);
    }
    
    /*
     * (non-PHPdoc) @see \Page\Manager\PageManagerInterface::findPageRepositoryBySlug()
     */
    public function findPageRepositoryBySlug($string,$language_id)
    {
        if (! is_string($string))
            throw new InvalidArgumentException(sprintf('Expected string but got %s', gettype($string)));
        
       
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Page\Entity\PageRepositoryInterface'))
            ->findOneBy(array(
            'slug' => (string) $string,
            'language' => (int) $language_id
        ));

        //fallback überprüfen
        if (! $entity) {
            $language_id = 0;
            $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
                ->resolveClassName('Page\Entity\PageRepositoryInterface'))
                ->findOneBy(array(
                    'slug' => (string) $string,
                    'language' => (int) $language_id
                ));
        }
            
        
        if (! $entity)
            throw new PageNotFoundException(sprintf('Could not find %s', $string));
        
       return $this->getPageRepository($entity->getId());
    }

    public function createPageEntity()
    {
        $page = $this->getClassResolver()->resolveClassName('Page\Entity\Page');
        return new $page();
    }

    public function createPageRevisionEntity()
    {
        $page = $this->getClassResolver()->resolveClassName('Page\Entity\PageRevision');
        return new $page();
    }
    
    public function createPageRepositoryEntity()
    {
        $page = $this->getClassResolver()->resolveClassName('Page\Entity\PageRepository');
        return new $page();
    }
    
    protected function createService(PageRepositoryInterface $entity)
    {
        /* @var $instance \Page\Service\PageServiceInterface */
        $instance = $this->createInstance('Page\Service\PageServiceInterface');
        $instance->setEntity($entity);
        $instance->setManager($this);
        $instance->getRepositoryManager()->addRepository($entity);
        return $instance; 
    }


    public function createPageRepository(array $data,$language){
        if ($data['fallback']==1) $language=$this->getLanguageManager()->getLanguage(0)->getEntity();
        $pageRepository = $this->createPageRepositoryEntity();
        $pageRepository->populate($data);
        $this->getUuidManager()->injectUuid($pageRepository);
        $pageRepository->setLanguage($language);
        $pageService = $this->createService($pageRepository);
        
        for ($i=0;$i<=$pageService->getNumberOfRoles();$i++) {
        
            if (array_key_exists($i,$data['roles'])) {
            $role = $pageService->getRoleById($i);
            if ($role!=null)
            $pageRepository->setRole($role);
            }
        }
        
        $this->getObjectManager()->persist($pageRepository);
        
        return $pageService;
        
    }

    public function createRevision(PageRepositoryInterface $repository, array $data) {
        $revision = $this->createPageRevisionEntity();
        $revision->populate($data);
        $revision->setAuthor($this->getUserManager()->getUserFromAuthenticator()->getEntity());
        $revision->untrash();
        $revision->setRepository($repository);
        $repository->addRevision($revision);
        $pageservice = $this->createService($repository);
        $pageservice->getRepositoryManager()->getRepository($repository)->addRevision($revision);
        $repository->setCurrentRevision($revision);
    
        $this->getObjectManager()->persist($repository);
        $this->getObjectManager()->persist($revision);
        return $pageservice;
    
    } 
    

}