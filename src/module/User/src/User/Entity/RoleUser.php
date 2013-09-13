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
use Core\Entity\AbstractEntity;

/**
 * A
 * user.
 *
 * @ORM\Entity
 * @ORM\Table(name="role_user")
 */
class RoleUser extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Role",
     * inversedBy="roleUsers")
     */
    protected $role;

    /**
     * @ORM\ManyToOne(targetEntity="User",
     * inversedBy="userRoles")
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="Language\Entity\Language")
     */
    protected $language;

    /**
     *
     * @return field_type
     *         $id
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     *
     * @return field_type
     *         $role
     */
    public function getRole ()
    {
        return $this->role;
    }

    /**
     *
     * @return field_type
     *         $user
     */
    public function getUser ()
    {
        return $this->user;
    }

    /**
     *
     * @return field_type
     *         $language
     */
    public function getLanguage ()
    {
        return $this->language;
    }

    /**
     *
     * @param field_type $id            
     * @return $this
     */
    public function setId ($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param field_type $role            
     * @return $this
     */
    public function setRole ($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     *
     * @param field_type $user            
     * @return $this
     */
    public function setUser ($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @param field_type $language            
     * @return $this
     */
    public function setLanguage ($language)
    {
        $this->language = $language;
        return $this;
    }
}