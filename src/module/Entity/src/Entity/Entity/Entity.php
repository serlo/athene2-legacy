<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity")
 */
class Entity extends AbstractEntity {

	/**
     * @ORM\ManyToMany(targetEntity="Entity")
     * @ORM\JoinTable(name="link",
     *      joinColumns={
     *      	@JoinColumn(name="child_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      	@JoinColumn(name="parent_id", referencedColumnName="id")
     *      }
     * )
	 */
	protected $parents;
	
	/**
     * @ORM\ManyToMany(targetEntity="Entity")
     * @ORM\JoinTable(
     * 		name="link",
     *      joinColumns={
     *      	@JoinColumn(name="child_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      	@JoinColumn(name="parent_id", referencedColumnName="id")
     *      }
     * )
	 */
	protected $children;
	
	/**
	 * @ORM\OneToMany(targetEntity="Repository", mappedBy="entity")
	 **/
	protected $repositories;
	
	/**
	 * @ORM\ManyToOne(targetEntity="EntityFactory", inversedBy="entities")
	 * @ORM\JoinColumn(name="entity_factory_id", referencedColumnName="id")
	 **/
	protected $factory;

	/**
	 * @ORM\ManyToOne(targetEntity="EntityType", inversedBy="entities")
	 * @ORM\JoinColumn(name="entity_type_id", referencedColumnName="id")
	 **/
	protected $type;

	/**
	 * @ORM\ManyToOne(targetEntity="Core\Entity\Language", inversedBy="entities")
	 **/
	protected $language;
	
	/** @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
	 */
	protected $date;
	
    /** @ORM\Column(type="boolean") */
	protected $killed;
	
    /** @ORM\Column(type="text",length=255) */
	protected $title;
	
    /** @ORM\Column(type="text",length=255) */
	protected $slug;
	
	public function __construct() {
        $this->repositories = new \Doctrine\Common\Collections\ArrayCollection();
	}
}