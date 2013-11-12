<?php
namespace Page\Service;

use Page\Manager\PageManagerInterface;
use Page\Entity\PageRevisionInterface;
use User\Entity\RoleInterface;
use Versioning\Entity\RevisionInterface;


class PageService implements PageServiceInterface  {

    use \Common\Traits\ObjectManagerAwareTrait;
    use \Versioning\RepositoryManagerAwareTrait;
    use\Common\Traits\EntityAwareTrait;
    
    
    
    /**
     *
     * @var PageManagerInterface
     */
    protected $manager;
    
    /**
     * @return PageManagerInterface $manager
     */
    public function getManager ()
    {
        return $this->manager;
    }
    
    /**
     * @param PageManagerInterface $manager
     * @return $this
     */
    public function setManager (PageManagerInterface $manager)
    {
        $this->manager = $manager;
        return $this;
    }
    
   
    
    public function getCurrentRevision(){
        
        return $this->getRepository()->getCurrentRevision();
        
    }
    
    public function getRevision($id){
        $revision = $this->getRepository()->getRevision($id);
        if (!$revision->isTrashed())
        return $revision;
        else
        return null;
  
    }
    
    
    public function hasCurrentRevision(){
        return $this->getRepository()->hasCurrentRevision();

    }
    
    public function setCurrentRevision($revision){
        $this->getRepository()->checkoutRevision($revision->getId());
        return $this;
    }
    
    
    public function setRole(RoleInterface $role){
        $this->entity->setRole($role); 
    }
    
    public function getRoleById($id){
    
        $repository = $this->getObjectManager()->getRepository('User\Entity\Role');
        $role     =   $repository->findOneBy(array('id' => $id)); 
        return $role;
    }
    
    public function countRoles(){
    
        $repository =     $this->getObjectManager()->getRepository('User\Entity\Role');
        $roles     = $repository->findAll();
        return count($roles);
    }
    
    public function hasRole($role){
        return $this->entity->hasRole($role);
    }
    
    public function hasPermission($userService){
        
        
        if ($userService==null) return false;
        
        $roles = $this->entity->getRoles();
        foreach($roles as $roleEntity) { 
            if($userService->hasRole($roleEntity->getName())){
                return true;
            }
        }
        return false;
    
    }
    
    protected function getRepository(){
        return $this->getRepositoryManager()->getRepository($this->entity);
    }


    public function deleteRevision($id) {
        $repository = $this->getRepository();
        $revision = $this->getRevision($id);
        $repository->removeRevision($id);
        $this->getObjectManager()->remove($revision);
        $this->getObjectManager()->flush();
        return $this;
    }

    public function trashRevision($id) {
        $repository = $this->getRepository();
        $revision = $this->getRevision($id);
        $revision->trash();
        $this->getObjectManager()->flush();
        return $this;
    }
    
    public function getRepositoryId() {
        return
        $this->getEntity()->getId();
    }
}

