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
use Zend\InputFilter\InputFilterInterface;
use User\Form\UserFilter;
use Core\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A
 * user.
 *
 * @ORM\Entity @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user")
 */
class User extends AbstractEntity
{

    private $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="UserLog",
     * mappedBy="user")
     */
    protected $logs;

    /**
     * @ORM\OneToMany(targetEntity="RoleUser",
     * mappedBy="user")
     */
    private $userRoles;

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
     * @ORM\Column(type="datetime",
     * nullable=true)
     * *
     */
    protected $last_login;

    /**
     * @ORM\Column(type="datetime")
     * *
     */
    protected $date;

    /**
     * @ORM\Column(type="string",
     * nullable=true)
     * *
     */
    protected $givenname;

    /**
     * @ORM\Column(type="string",
     * nullable=true)
     * *
     */
    protected $lastname;

    /**
     * @ORM\Column(type="boolean",
     * nullable=true)
     * *
     */
    protected $gender;

    /**
     * @ORM\Column(type="boolean")
     * *
     */
    protected $ads_enabled;

    /**
     * @ORM\Column(type="boolean")
     * *
     */
    protected $removed;

    /**
     *
     * @return the
     *         $userRoles
     */
    public function getUserRoles ()
    {
        return $this->userRoles;
    }

    /**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     *         $logs
     */
    public function getLogs ()
    {
        return $this->logs;
    }

    /**
     *
     * @return array
     *         $email
     */
    public function getEmail ()
    {
        return $this->email;
    }

    /**
     *
     * @return array
     *         $username
     */
    public function getUsername ()
    {
        return $this->username;
    }

    public function getName ()
    {
        return $this->getUsername();
    }

    /**
     *
     * @return array
     *         $password
     */
    public function getPassword ()
    {
        return $this->password;
    }

    /**
     *
     * @return number
     *         $logins
     */
    public function getLogins ()
    {
        return $this->logins;
    }

    /**
     *
     * @return field_type
     *         $last_login
     */
    public function getLast_login ()
    {
        return $this->last_login;
    }

    /**
     *
     * @return field_type
     *         $date
     */
    public function getDate ()
    {
        return $this->date;
    }

    /**
     *
     * @return field_type
     *         $givenname
     */
    public function getGivenname ()
    {
        return $this->givenname;
    }

    /**
     *
     * @return field_type
     *         $lastname
     */
    public function getLastname ()
    {
        return $this->lastname;
    }

    /**
     *
     * @return field_type
     *         $gender
     */
    public function getGender ()
    {
        return $this->gender;
    }

    /**
     *
     * @return boolean
     *         $ads_enabled
     */
    public function getAds_enabled ()
    {
        return $this->ads_enabled;
    }

    /**
     *
     * @return boolean
     *         $removed
     */
    public function getRemoved ()
    {
        return $this->removed;
    }

    /**
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $logs            
     * @return $this
     */
    public function setLogs ($logs)
    {
        $this->logs = $logs;
        return $this;
    }

    /**
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $userRoles            
     * @return $this
     */
    public function setUserRoles ($userRoles)
    {
        $this->userRoles = $userRoles;
        return $this;
    }

    /**
     *
     * @param array $email            
     * @return $this
     */
    public function setEmail ($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     *
     * @param array $username            
     * @return $this
     */
    public function setUsername ($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     *
     * @param array $password            
     * @return $this
     */
    public function setPassword ($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     *
     * @param number $logins            
     * @return $this
     */
    public function setLogins ($logins)
    {
        $this->logins = $logins;
        return $this;
    }

    /**
     *
     * @param field_type $last_login            
     * @return $this
     */
    public function setLast_login ($last_login)
    {
        $this->last_login = $last_login;
        return $this;
    }

    /**
     *
     * @param field_type $date            
     * @return $this
     */
    public function setDate ($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     *
     * @param field_type $givenname            
     * @return $this
     */
    public function setGivenname ($givenname)
    {
        $this->givenname = $givenname;
        return $this;
    }

    /**
     *
     * @param field_type $lastname            
     * @return $this
     */
    public function setLastname ($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     *
     * @param field_type $gender            
     * @return $this
     */
    public function setGender ($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     *
     * @param boolean $ads_enabled            
     * @return $this
     */
    public function setAds_enabled ($ads_enabled)
    {
        $this->ads_enabled = $ads_enabled;
        return $this;
    }

    /**
     *
     * @param boolean $removed            
     * @return $this
     */
    public function setRemoved ($removed)
    {
        $this->removed = $removed;
        return $this;
    }

    public function __construct ()
    {
        $this->userRoles = new ArrayCollection();
        $this->logs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Populate
     * from
     * an
     * array.
     *
     * @param array $data            
     */
    public function populate (array $data = array())
    {
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->username = $data['username'];
        $this->logins = $this->logins ? $this->logins : 0;
        $this->ads_enabled = $this->ads_enabled ? $this->ads_enabled : true;
        $this->removed = $this->removed ? $this->removed : false;
    }

    public function setInputFilter (InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter ()
    {
        if (! $this->inputFilter) {
            $this->inputFilter = new UserFilter();
        }
        
        return $this->inputFilter;
    }
}