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
	/**
	 * @OneToMany(targetEntity="Entity\Entity\Entity", mappedBy="language")
	 **/
	protected $entities;
	
    function __construct ()
    {
        $this->entities = new \Doctrine\Common\Collections\ArrayCollection();    	
    }
}