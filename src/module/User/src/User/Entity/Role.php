<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Core\Entity\Model;
use Core\Entity\Language;
use Core\Entity\Subject;

/**
 * A role.
 * 
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role extends Model
{
    /**
     * @ORM\Column(type="string") *
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $description;
    
    /**
     * @ORM\OneToMany(targetEntity="RoleUser", mappedBy="role")
     **/
    private $roleUsers;

    public function __construct() {
    	$this->roleUsers = new ArrayCollection();
    }
}