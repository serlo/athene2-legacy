<?php

/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Jakob Pfab (jakob.pfab@serlo.org)
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Page\Manager;

use Page\Manager\PageManagerInterface;
use Page\Entity\Page;
use Page\Entity\PageRepositoryInterface;
use Page\Exception\PageNotFoundException;
use Page\Exception\InvalidArgumentException;
use Page\Service\PageServiceInterface;
use Language\Entity\LanguageInterface;

class PageManager implements PageManagerInterface
{
    
    use \Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait;//,\Common\Traits\EntityDelegatorTrait;
    use \Page\Manager\PageManagerAwareTrait;
    use \Uuid\Manager\UuidManagerAwareTrait;
    use \Language\Manager\LanguageManagerAwareTrait;
    use \User\Manager\UserManagerAwareTrait;
    use \License\Manager\LicenseManagerAwareTrait;
    
    /*
     * (non-PHPdoc) @see \Page\Manager\PageManagerInterface::getPageRepository()
     */
    public function getRevision($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        $revision = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Page\Entity\PageRevisionInterface'), $id);
        if (!$revision)
            throw new PageNotFoundException(sprintf('Page Revision %s not found', $id));
         
        // $revision = $this->getRepository()->getRevision($id);
        if (! $revision->isTrashed())
            return $revision;
        else
            return null;
    }

    public function getPageRepository($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $pageRepository = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Page\Entity\PageRepositoryInterface'), $id);
            if (! $pageRepository)
                throw new PageNotFoundException(sprintf('PageRepository %s not found', $id));
            
            $instance = $this->createService($pageRepository);
            $this->addInstance($pageRepository->getId(), $instance);
            return $instance;
        }
        
        return $this->getInstance($id);
    }

    protected function createPageRevisionEntity()
    {
        $revision = $this->getClassResolver()->resolve('Page\Entity\PageRevisionInterface');
        $uuidManager = $this->getUuidManager();
        $uuidManager->injectUuid($revision);
        return $revision;
    }

    protected function createPageRepositoryEntity()
    {
        $repository = $this->getClassResolver()->resolve('Page\Entity\PageRepositoryInterface');
        $this->getUuidManager()->injectUuid($repository);
        
        $license = $this->getLicenseManager()->getLicense(1); // Finds a license with the id 3
        $this->getLicenseManager()->injectLicense($repository, $license);
       // $this->getLicenseManager()->injectLicense($repository);
        return $repository;
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

    public function createPageRepository(array $data, $language)
    {
        $pageRepository = $this->createPageRepositoryEntity();
        
        $pageRepository->populate($data);
        
        $pageRepository->setLanguage($language);
        $pageService = $this->createService($pageRepository);
        
        for ($i = 0; $i <= $pageService->countRoles(); $i ++) {
            
            if (array_key_exists($i, $data['roles'])) {
                $role = $pageService->getRoleById($i);
                if ($role != null) {
                    $pageRepository->setRole($role);
                }
            }
        }
        
        $this->getObjectManager()->persist($pageRepository);
        
        return $pageService;
    }

    public function createRevision(PageRepositoryInterface $repository, array $data)
    {
        $revision = $this->createPageRevisionEntity();
        $revision->populate($data);
        $revision->setAuthor($data['author']);
        $revision->untrash();
        $revision->setRepository($repository);
        $repository->addRevision($revision);
        $pageservice = $this->createService($repository);
        $pageservice->getRepositoryManager()
            ->getRepository($repository)
            ->addRevision($revision);
        $repository->setCurrentRevision($revision);
        
        $this->getObjectManager()->persist($repository);
        $this->getObjectManager()->persist($revision);
        return $pageservice;
    
    } 
    
    public function findAllRepositorys(LanguageInterface $language){
        return
             $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Page\Entity\PageRepositoryInterface'))
            ->findBy(array(
            'language' => $language->getId()
        ));
    }
}