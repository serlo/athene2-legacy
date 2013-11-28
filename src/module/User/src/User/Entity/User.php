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
use Uuid\Entity\UuidEntity;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user")
 */
class User extends UuidEntity implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="role_user")
     */
    protected $roles;

    /**
     * @ORM\Column(type="string",
     * unique=true)
     * *
     */
    protected $email;

    /**
     * @ORM\Column(type="string",
     * unique=true)
     * *
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     * *
     */
    protected $password;

    /**
     * @ORM\Column(type="integer")
     * *
     */
    protected $logins;

    /**
     * @ORM\Column(type="string")
     */
    protected $token;

    /**
     * @ORM\Column(type="datetime",
     * nullable=true)
     */
    protected $last_login;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     *
     * @return field_type $token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     *
     * @param field_type $token            
     * @return $this
     */
    public function generateToken()
    {
        $this->token = hash('crc32b', uniqid('user.token.', true));
        ;
        return $this;
    }

    /**
     *
     * @return array $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     *
     * @return array $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @return number $logins
     */
    public function getLogins()
    {
        return $this->logins;
    }

    /**
     *
     * @return field_type $last_login
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }

    /**
     *
     * @return field_type $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @param array $email            
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     *
     * @param array $username            
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     *
     * @param array $password            
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     *
     * @param number $logins            
     * @return $this
     */
    public function setLogins($logins)
    {
        $this->logins = $logins;
        return $this;
    }

    /**
     *
     * @param field_type $last_login            
     * @return $this
     */
    public function setLastLogin($last_login)
    {
        $this->last_login = $last_login;
        return $this;
    }

    /**
     *
     * @param field_type $date            
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->ads_enabled = true;
        $this->removed = false;
        $this->logins = 0;
        $this->generateToken();
    }

    public function addRole(RoleInterface $role)
    {
        $this->roles->add($role);
        return $this;
    }

    public function removeRole(RoleInterface $role)
    {
        $this->roles->removeElement($role);
        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Populate from an array.
     *
     * @param array $data            
     */
    public function populate(array $data = array())
    {
        $this->injectArray('email', $data);
        $this->injectArray('password', $data);
        $this->injectArray('username', $data);
        $this->injectArray('logins', $data);
        $this->injectArray('ads_enabled', $data);
        $this->injectArray('removed', $data);
        $this->injectArray('lastname', $data);
        $this->injectArray('givenname', $data);
        $this->injectArray('gender', $data);
        return $this;
    }

    private function injectArray($key, array $array, $default = NULL)
    {
        if (array_key_exists($key, $array)) {
            $this->$key = $array[$key];
        }
        return $this;
    }

    public function hasRole($id)
    {
        $roles = $this->getRoles();
        foreach ($roles as $roleEntity) {
            if (is_numeric($id)) {
                if ($roleEntity->getId() == $id) {
                    return true;
                }
            } elseif (is_string($id)) {
                if ($roleEntity->getName() == $id) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getRoleNames()
    {
        $return = array();
        foreach ($this->getRoles() as $role) {
            $return[] = $role->getName();
        }
        return $return;
    }
}