<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Core\Entity\AbstractEntity;

/**
 * A user.
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
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="roleUsers")
     **/
    protected $role;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userRoles")
     **/
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="Core\Entity\Language")
     **/
    protected $language;
    
    /**
     * @ORM\OneToOne(targetEntity="Core\Entity\Subject")
     **/
    protected $subject;
}