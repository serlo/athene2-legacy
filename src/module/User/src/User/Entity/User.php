<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterInterface;
use User\Form\UserFilter;
use Core\Entity\Model;

/**
 * A user.
 * 
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends Model
{

    private $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true) *
     */
    protected $email;

    /**
     * @ORM\Column(type="string", unique=true) *
     */
    protected $username;

    /**
     * @ORM\Column(type="string") *
     */
    protected $password;

    /**
     * @ORM\Column(type="integer") *
     */
    protected $logins;

    /**
     * @ORM\Column(type="datetime", nullable=true) *
     */
    protected $last_login;

    /**
     * @ORM\Column(type="datetime") *
     */
    protected $date;

    /**
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $givenname;

    /**
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $lastname;

    /**
     * @ORM\Column(type="boolean", nullable=true) *
     */
    protected $gender;

    /**
     * @ORM\Column(type="boolean") *
     */
    protected $ads_enabled;

    /**
     * @ORM\Column(type="boolean") *
     */
    protected $removed;

    /**
     * Populate from an array.
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