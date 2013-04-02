<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**    
 * A language.
 * 
 * @ORM\Entity
 * @ORM\Table(name="language")  
 */
class Language extends AbstractEntity
{
    function __construct ()
    {
    	
    }
}