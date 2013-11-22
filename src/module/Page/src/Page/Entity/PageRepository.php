<?php
namespace Page\Entity;

use Doctrine\ORM\Mapping as ORM;
use Uuid\Entity\UuidEntity;
use Versioning\Entity\RepositoryInterface;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;
use User\Entity\RoleInterface;

/**
 * A page repository.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_repository")
 * 
 */
class PageRepository extends UuidEntity implements RepositoryInterface,PageRepositoryInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="pageRepository")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;
	
    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="page_repository_role",
     *      joinColumns={@ORM\JoinColumn(name="page_repository_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
	protected $roles;
	
	 /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language") *
     */
    protected $language;
	
	
	  /**
     * @ORM\OneToOne(targetEntity="PageRevision")
     * @ORM\JoinColumn(name="current_revision_id", referencedColumnName="id")
     */
	protected $current_revision;
	
	
	/**
	 * @ORM\OneToMany(targetEntity="PageRevision", mappedBy="page_repository", cascade="persist")
	 */
	protected $revisions;
	
	public function __construct()
	{
	    $this->revisions = new ArrayCollection();
	    $this->roles = new ArrayCollection();
	    
	}
	

	/**
     * @return the $role
     */
    public function getRoles()
    {
        return $this->roles;
    }

	/**
     * @return the $language
     */
    public function getLanguage()
    {
        return $this->language;
    }


	

	/**
     * @param field_type $role
     */
    public function setRole(RoleInterface $role)
    {
        $this->roles->add($role);
    }
    
    public function setRoles(ArrayCollection $roles)
    {
        $this->roles->clear();
        $this->roles=$roles();
    }
    
    public function hasRole(RoleInterface $role){
        return $this->roles->contains($role);
    }
    
    

	/**
     * @param field_type $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }


	/**
     * @param field_type $revisions
     */
    public function setRevisions($revisions)
    {
        $this->revisions = $revisions;
    }

	public function __get ($property)
	{
		return $this->$property;
	}

	/**
	 * Magic setter to save protected properties.
	 *
	 * @param string $property
	 * @param mixed $value
	 *
	 */
	public function __set ($property, $value)
	{
		$this->$property = $value;
	}

	
	public function getRevisions() {
		
		return $this->revisions;
		
	}
	
	public function newRevision() {
	    $revision = new PageRevision();
	    $revision->setRepository($this);
	    return $revision;
	}
	
	/* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::getCurrentRevision()
     */
    public function getCurrentRevision()
    {
        return $this->current_revision;
    }

	/* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::hasCurrentRevision()
     */
    public function hasCurrentRevision()
    {
        return $this->getCurrentRevision() !== NULL;        
    }

	/* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::setCurrentRevision()
     */
    public function setCurrentRevision(\Versioning\Entity\RevisionInterface $revision)
    {
        $this->current_revision=$revision;
        
    }
    


    public function populate(array $data = array())
    {
        $this->injectFromArray('role', $data);
        $this->injectFromArray('language', $data);
        $this->injectFromArray('current_revision', $data);
        return $this;
    }
    
    private function injectFromArray($key, array $array, $default = NULL)
    {
        if (array_key_exists($key, $array)) {
            $this->$key = $array[$key];
        } elseif ($default !== NULL) {
            $this->$key = $default;
        }
    }
	/* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::addRevision()
     */
    public function addRevision(\Versioning\Entity\RevisionInterface $revision)
    {
        $this->revisions->add($revision);
        $revision->setRepository($this);
	    return $revision;
        
    }

	/* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::removeRevision()
     */
    public function removeRevision(\Versioning\Entity\RevisionInterface $revision)
    {
        
       if ( $this->getCurrentRevision() == $revision)
         $this->current_revision=NULL;
        
       
        
        $this->revisions->removeElement($revision);
     
        
    }
    


    
}


