<?php
namespace Page\Entity;

use Doctrine\ORM\Mapping as ORM;
use Uuid\Entity\UuidEntity;
use Versioning\Entity\RepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use User\Entity\RoleInterface;
use License\Entity\LicenseAwareInterface;
use License\Entity\LicenseInterface;

/**
 * A page repository.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_repository")
 */
class PageRepository extends UuidEntity implements RepositoryInterface, PageRepositoryInterface, LicenseAwareInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="pageRepository", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $uuid;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="page_repository_role",
     * joinColumns={@ORM\JoinColumn(name="page_repository_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
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

    /**
     * @ORM\ManyToOne(targetEntity="License\Entity\LicenseInterface")
     */
    protected $license;

    public function getLicense()
    {
        return $this->license;
    }

    public function setLicense(LicenseInterface $license)
    {
        $this->license = $license;
        return $this;
    }

    public function __construct()
    {
        $this->revisions = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    /**
     *
     * @return the $role
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     *
     * @return the $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     *
     * @param field_type $role            
     */
    public function setRole(RoleInterface $role)
    {
        $this->roles->add($role);
        return $this;
    }

    public function setRoles(ArrayCollection $roles)
    {
        $this->roles->clear();
        $this->roles = $roles;
        return $this;
    }

    public function hasRole(RoleInterface $role)
    {
        return $this->roles->contains($role);
    }

    /**
     *
     * @param field_type $language            
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     *
     * @param field_type $revisions            
     */
    public function setRevisions($revisions)
    {
        $this->revisions = $revisions;
        return $this;
    }

    public function getRevisions()
    {
        return $this->revisions;
    }

    public function createRevision()
    {
        $revision = new PageRevision();
        $revision->setRepository($this);
        return $revision;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::getCurrentRevision()
     */
    public function getCurrentRevision()
    {
        return $this->current_revision;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::hasCurrentRevision()
     */
    public function hasCurrentRevision()
    {
        return $this->getCurrentRevision() !== NULL;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::setCurrentRevision()
     */
    public function setCurrentRevision(\Versioning\Entity\RevisionInterface $revision)
    {
        $this->current_revision = $revision;
        return $this;
    }

    public function getRevision($id)
    {
        throw new \EXCEPTION();
    }

    public function populate(array $data = array())
    { // CHECK THIS AGAIN
      // $this->injectFromArray('role', $data);
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
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::addRevision()
     */
    public function addRevision(\Versioning\Entity\RevisionInterface $revision)
    {
        $this->revisions->add($revision);
        $revision->setRepository($this);
        return $revision;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::removeRevision()
     */
    public function removeRevision(\Versioning\Entity\RevisionInterface $revision)
    {
        if ($this->getCurrentRevision() == $revision)
            $this->current_revision = NULL;
        $this->revisions->removeElement($revision);
        return $this;
    }
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::isRevised()
     */
    public function isUnrevised()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::getHead()
     */
    public function getHead()
    {
        // TODO Auto-generated method stub
    }
}


