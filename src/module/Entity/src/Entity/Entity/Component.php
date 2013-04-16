<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="component")
 */
class Component extends AbstractEntity { 

	/**
	 * @OneToMany(targetEntity="Entity", mappedBy="component")
	 **/
	protected $entities;
	
    public function __construct() {
        $this->entities = new \Doctrine\Common\Collections\ArrayCollection();
    }
}