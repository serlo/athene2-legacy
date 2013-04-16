<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity type.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_type")
 */
class EntityType extends AbstractEntity {   

	/**
	 * @OneToMany(targetEntity="Entity", mappedBy="component")
	 **/
	protected $entities;
	
    public function __construct() {
        $this->entities = new \Doctrine\Common\Collections\ArrayCollection();
    }
}