<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A role.
 *
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role extends \Rbac\Role\HierarchicalRole implements RoleInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\Column(type="string") *
     */
    protected $name;
    /**
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $description;
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="roles")
     * @ORM\JoinTable(name="role_user")
     */
    protected $users;
    /**
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="roles", indexBy="name")
     * @ORM\JoinTable(name="role_permission")
     */
    protected $permissions;
    /**
     * @ORM\OneToMany(targetEntity="Role", mappedBy="parent")
     */
    protected $children;
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function addUser(UserInterface $user)
    {
        $this->users->add($user);
    }

    public function removeUser(UserInterface $user)
    {
        $this->users->removeElement($user);
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function addPermission($permission)
    {
        $this->permissions->add($permission);
    }

    public function removePermission($permission)
    {
        $this->permissions->removeElement($permission);
    }

    public function hasPermission($permission)
    {
        /* @var $instancePermission PermissionInterface */
        foreach($this->getPermissions() as $instancePermission){
            if(is_numeric($permission) ){
                if($instancePermission->getId() == $permission){
                    return true;
                }
            } else {
                if($instancePermission->getName() == $permission){
                    return true;
                }
            }
        }
        return false;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
