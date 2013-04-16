<?php
namespace User\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="user_log")
 */
class UserLog extends AbstractEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="logs")
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;

    /**
     * @ORM\Column(type="string", unique=true) *
     */
    protected $action;

    /**
     * @ORM\Column(type="integer") *
     */
    protected $ref_id;

    /**
     * @ORM\Column(type="string") *
     */
    protected $ref;

    /**
     * @ORM\Column(type="string") *
     */
    protected $note;
    
    /**
     * @ORM\Column(type="string") *
     */
    protected $event;
    
    /**
     * @ORM\Column(type="string") *
     */
    protected $source;
}