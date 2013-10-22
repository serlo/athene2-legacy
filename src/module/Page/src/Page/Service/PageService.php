<?php
namespace Page\Service;

use Page\Manager\PageManagerInterface;
use Page\Entity\PageRevisionInterface;
use User\Entity\RoleInterface;


class PageService implements PageServiceInterface  {

    use \Common\Traits\ObjectManagerAwareTrait;
    use \Versioning\RepositoryManagerAwareTrait;
    use\Common\Traits\EntityAwareTrait;
    
    protected $repository;
    
    
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
    
    public function editCurrentRevision(PageRevisionInterface $revision,array $array){
        
        $revision->setContent($array['content']);
        $revision->setTitle($array['title']);
        return $this;
    }
    
    
    public function getCurrentRevision(){
        
        return $this->getRepositoryManager()->getRepository($this->entity)->getCurrentRevision();
        
    }
    
    public function getRevision($id){
    
		$repository =     $this->getObjectManager()->getRepository('Page\Entity\PageRevision');
		$revision     = $repository->findOneBy(array('id' => $id));

		return $revision;    
    }
    
    public function getContentAndTitleFromRevision(PageRevisionInterface $revision) {
    
        $array = array("content" => $revision->getContent(),
            "title" => $revision->getTitle());
        
        return $array;
    }
    
    public function hasCurrentRevision(){
        return $this->getRepositoryManager()->getRepository($this->entity)->hasCurrentRevision();

    }
    
    public function setCurrentRevision($revision){
        $this->entity->setCurrentRevision($revision);
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
    
    public function getNumberOfRoles(){
    
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


}

?>