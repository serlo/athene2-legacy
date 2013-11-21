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
}