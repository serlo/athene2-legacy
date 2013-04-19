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
     * @ORM\ManyToMany(targetEntity="Entity", mappedBy="children")
     * @ORM\JoinTable(name="link",
     *      joinColumns={
     *      	@ORM\JoinColumn(name="child_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      	@ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     *      }
     * )
	 */
	protected $parents;
	
	/**
     * @ORM\ManyToMany(targetEntity="Entity", mappedBy="parents")
     * @ORM\JoinTable(
     * 		name="link",
     *      joinColumns={
     *      	@ORM\JoinColumn(name="child_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      	@ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     *      }
     * )
	 */
	protected $children;
	
	/**
	 * @ORM\OneToMany(targetEntity="Revision", mappedBy="repository")
	 **/
	protected $revisions;

	/**
	 * @ORM\OneToOne(targetEntity="Revision")
	 * @ORM\JoinColumn(name="current_revision_id", referencedColumnName="id")
	 **/
	protected $currentRevision;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Factory", inversedBy="entities")
	 * @ORM\JoinColumn(name="entity_factory_id", referencedColumnName="id")
	 **/
	protected $factory;

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
	protected $slug;
	
	public function __construct() {
        $this->revisions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parents = new \Doctrine\Common\Collections\ArrayCollection();
	}
}