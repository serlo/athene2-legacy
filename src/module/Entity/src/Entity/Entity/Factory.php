<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity type.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_factory")
 */
class Factory extends AbstractEntity {   

	/**
	 * @ORM\OneToMany(targetEntity="Entity", mappedBy="factory")
	 **/
	protected $entities;
	
	/** @ORM\Column(type="text",length=255,name="class_name") */
	protected $className;
	
    public function __construct() {
        $this->entities = new \Doctrine\Common\Collections\ArrayCollection();
    }
}