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
	/**
	 * @OneToMany(targetEntity="Entity\Entity\Entity", mappedBy="subject")
	 **/
	protected $entities;
	
    function __construct ()
    {
        $this->entities = new \Doctrine\Common\Collections\ArrayCollection();    	
    }
}