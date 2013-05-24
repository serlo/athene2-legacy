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
	/**
     * @return \Doctrine\Common\Collections\ArrayCollection $entities
     */
    public function getEntities ()
    {
        return $this->entities;
    }

	/**
     * @return field_type $className
     */
    public function getName ()
    {
        return $this->className;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $entities
     * @return $this
     */
    public function setEntities ($entities)
    {
        $this->entities = $entities;
        return $this;
    }

	/**
     * @param field_type $className
     * @return $this
     */
    public function setName ($className)
    {
        $this->className = $className;
        return $this;
    }

}