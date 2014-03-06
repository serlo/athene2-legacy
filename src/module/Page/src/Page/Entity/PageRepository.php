<?php
namespace Page\Entity;

use Authorization\Entity\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Instance\Entity\InstanceAwareTrait;
use License\Entity\LicenseInterface;
use Uuid\Entity\Uuid;
use Versioning\Entity\RevisionInterface;

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

    public function addRevision(RevisionInterface $revision)
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

    public function hasCurrentRevision()
    {
        return $this->getCurrentRevision() !== null;
    }

    public function getCurrentRevision()
    {
        return $this->current_revision;
    }

    public function setCurrentRevision(RevisionInterface $revision)
    {
        $this->current_revision = $revision;
    }

    public function removeRevision(RevisionInterface $revision)
    {
        if ($this->getCurrentRevision() == $revision) {
            $this->current_revision = null;
        }
        $this->revisions->removeElement($revision);
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(ArrayCollection $roles)
    {
        $this->roles->clear();
        $this->roles = $roles;
    }

    public function hasRole(RoleInterface $role)
    {
        return $this->roles->contains($role);
    }

    public function populate(array $data = [])
    {
        $this->injectFromArray('instance', $data);
        $this->injectFromArray('current_revision', $data);
    }

    public function addRole(RoleInterface $role)
    {
        $this->roles->add($role);
    }

    private function injectFromArray($key, array $array, $default = null)
    {
        if (array_key_exists($key, $array)) {
            $this->$key = $array[$key];
        } elseif ($default !== null) {
            $this->$key = $default;
        }
    }
}
