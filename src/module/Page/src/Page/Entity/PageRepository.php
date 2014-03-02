<?php
namespace Page\Entity;

use Authorization\Entity\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Instance\Entity\InstanceAwareTrait;
use License\Entity\LicenseInterface;
use Uuid\Entity\Uuid;

/**
 * A page repository.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_repository")
 */
class PageRepository extends Uuid implements PageRepositoryInterface
{
    use InstanceAwareTrait;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="page_repository_role",
     *      joinColumns={@ORM\JoinColumn(name="page_repository_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    protected $roles;


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

    public function __construct()
    {
        $this->revisions = new ArrayCollection();
        $this->roles     = new ArrayCollection();

    }

    public function addRevision(\Versioning\Entity\RevisionInterface $revision)
    {
        $this->revisions->add($revision);
        $revision->setRepository($this);

        return $revision;

    }

    public function createRevision()
    {
        $revision = new PageRevision();
        $revision->setRepository($this);

        return $revision;

    }

    public function getLicense()
    {
        return $this->license;
    }

    public function setLicense(LicenseInterface $license)
    {
        $this->license = $license;

        return $this;
    }

    public function getRevisions()
    {
        $revisions = [];
        foreach ($this->revisions as $revision) {
            if (!$revision->isTrashed()) {
                $revisions[] = $revision;
            }
        }

        return $revisions;

    }

    /**
     * @param field_type $revisions
     */
    public function setRevisions($revisions)
    {
        $this->revisions = $revisions;

        return $this;
    }

    public function hasCurrentRevision()
    {
        return $this->getCurrentRevision() !== null;
    }

    public function getCurrentRevision()
    {
        return $this->current_revision;
    }


    /* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::getCurrentRevision()
     */

    public function setCurrentRevision(\Versioning\Entity\RevisionInterface $revision)
    {
        $this->current_revision = $revision;

        return $this;

    }

    /* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::hasCurrentRevision()
     */

    public function removeRevision(\Versioning\Entity\RevisionInterface $revision)
    {

        if ($this->getCurrentRevision() == $revision) {
            $this->current_revision = null;
        }
        $this->revisions->removeElement($revision);

        return $this;

    }

    /* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::setCurrentRevision()
     */

    /**
     * @return the $role
     */
    public function getRoles()
    {
        return $this->roles;
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

    /* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::addRevision()
     */

    public function populate(array $data = [])
    {
        $this->injectFromArray('instance', $data);
        $this->injectFromArray('current_revision', $data);

        return $this;
    }

    /* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::removeRevision()
     */

    private function injectFromArray($key, array $array, $default = null)
    {
        if (array_key_exists($key, $array)) {
            $this->$key = $array[$key];
        } elseif ($default !== null) {
            $this->$key = $default;
        }
    }


    /* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::createRevision()
     */

    /**
     * @param field_type $role
     */
    public function setRole(RoleInterface $role)
    {
        $this->roles->add($role);

        return $this;
    }


}


