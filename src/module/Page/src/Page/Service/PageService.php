<?php
namespace Page\Service;

use Page\Manager\PageManagerInterface;
use User\Entity\RoleInterface;
use Common\Normalize\Normalizable;
use Common\Normalize\Normalized;

class PageService implements PageServiceInterface
{
    
    use \Common\Traits\ObjectManagerAwareTrait;
    use \Versioning\RepositoryManagerAwareTrait;
    use\Common\Traits\EntityAwareTrait;

    /**
     *
     * @var PageManagerInterface
     */
    protected $manager;

    /**
     *
     * @return PageManagerInterface $manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     *
     * @param PageManagerInterface $manager            
     * @return self
     */
    public function setManager(PageManagerInterface $manager)
    {
        $this->manager = $manager;
        return $this;
    }

    public function getCurrentRevision()
    {
        $repository = $this->getRepository();
        return $repository->getCurrentRevision();
    }

    public function hasCurrentRevision()
    {
        return $this->getRepository()->hasCurrentRevision();
    }

    public function setCurrentRevision($revision)
    {
        $this->getRepository()->checkoutRevision($revision->getId());
        return $this;
    }

    public function setRole(RoleInterface $role)
    {
        $this->entity->setRole($role);
    }

    public function getRoles(){
        $this->entity->getRoles();
    }
    
    public function getRoleById($id)
    {
        $repository = $this->getObjectManager()->getRepository('User\Entity\Role');
        $role = $repository->findOneBy(array(
            'id' => $id
        ));
        return $role;
    }

    public function countRoles()
    {
        $repository = $this->getObjectManager()->getRepository('User\Entity\Role');
        $roles = $repository->findAll();
        return count($roles);
    }

    public function hasRole($role)
    {
        return $this->entity->hasRole($role);
    }
/*
    public function hasPermission($userService)
    {
        if ($userService == null)
            return false;
        
        $roles = $this->entity->getRoles();
        foreach ($roles as $roleEntity) {
            if ($userService->hasRole($roleEntity->getName())) {
                return true;
            }
        }
        return false;
    }*/

    protected function getRepository()
    {
        $repositoryManager = $this->getRepositoryManager();
        $repository =  $repositoryManager->getRepository($this->entity);
        return $repository;
    }

    public function deleteRevision($id)
    {
        $repository = $this->getRepository();
        $revision = $this->getManager()->getRevision($id);
        $repository->removeRevision($id);
        $this->getObjectManager()->remove($revision);

        return $this;
    }

    public function trashRevision($id)
    {
        $revision = $this->getManager()->getRevision($id);
        $revision->trash();
        return $this;
    }

    public function getRepositoryId()
    {
        return $this->getEntity()->getId();
    }
}

