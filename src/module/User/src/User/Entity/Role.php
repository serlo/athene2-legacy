<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * A role.
 *
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role implements RoleInterface
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
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="roles")
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

    /**
     *
     * @return field_type $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return field_type $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return field_type $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param field_type $name            
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param field_type $description            
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function addUser(UserInterface $user)
    {
        $this->users->add($user);
        return $this;
    }

    public function removeUser(UserInterface $user)
    {
        $this->users->removeElement($user);
        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function addPermission($name)
    {
        $permission = new Permission();
        $permission->setName($name);
        $this->permissions->add($permission);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Zend\Permissions\Rbac\RoleInterface::hasPermission()
     */
    public function hasPermission($name)
    {
        return $this->permissions->filter(function (Permission $p) use ($name)
        {
            return $p->getName() == $name;
        })->count() > 0;
    }
    
    /*
     * (non-PHPdoc) @see \Zend\Permissions\Rbac\RoleInterface::addChild()
     */
    public function addChild($child)
    {
        $role = new self();
        $this->children->add($child);
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Zend\Permissions\Rbac\RoleInterface::setParent()
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Zend\Permissions\Rbac\RoleInterface::getParent()
     */
    public function getParent()
    {
        return $this->getParent();
    }
    
    /*
     * (non-PHPdoc) @see RecursiveIterator::hasChildren()
     */
    public function hasChildren()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see RecursiveIterator::getChildren()
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /*
     * (non-PHPdoc) @see RecursiveIterator::current()
     */
    public function current()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see RecursiveIterator::next()
     */
    public function next()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see RecursiveIterator::key()
     */
    public function key()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see RecursiveIterator::valid()
     */
    public function valid()
    {
        // TODO Auto-generated method stub
    }
    
    /*
     * (non-PHPdoc) @see RecursiveIterator::rewind()
     */
    public function rewind()
    {
        // TODO Auto-generated method stub
    }
}