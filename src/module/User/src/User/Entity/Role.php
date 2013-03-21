<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A role.
 * 
 * @ORM\Entity
 * @ORM\Table(name="rike")
 */
class User
{

    private $inputFilter;

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
}