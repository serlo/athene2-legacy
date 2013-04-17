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
	 * @OneToMany(targetEntity="Repository", mappedBy="entity")
	 **/
	protected $repositories;
	
	/**
	 * @ManyToOne(targetEntity="Component", inversedBy="entities")
	 **/
	protected $component;

	/**
	 * @ManyToOne(targetEntity="EntityType", inversedBy="entities")
	 * @JoinColumn(name="entity_type_id", referencedColumnName="id")
	 **/
	protected $type;

	/**
	 * @ManyToOne(targetEntity="Core\Entity\Subject", inversedBy="entities")
	 **/
	protected $subject;

	/**
	 * @ManyToOne(targetEntity="Core\Entity\Language", inversedBy="entities")
	 **/
	protected $language;
	
	/* protected $period;
	protected $license;
	protected $localization; */
	
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