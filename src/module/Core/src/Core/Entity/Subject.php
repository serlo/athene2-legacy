<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A subject.
 * 
 * @ORM\Entity
 * @ORM\Table(name="subject") 
 */
class Subject extends AbstractEntity
{
    function __construct ()
    {
    	
    }
}