<?php

namespace Taxonomy\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="taxonomy_term")
 */
class TaxonomyTerm extends AbstractEntity {
	/**
	 * @ORM\ManyToOne(targetEntity="Taxonomy", inversedBy="terms")
	 **/
	protected $taxonomy;

	protected $parent;	
	protected $children;
	
    /** @ORM\Column(type="integer") */
	protected $order;

	/** @ORM\Column(type="text",length=255,name="term") */
	protected $name;

	/** @ORM\Column(type="text",length=255) */
	protected $slug;
	
	/**
	 * @ORM\ManyToMany(targetEntity="\Entity\Entity\Entity")
	 * @ORM\JoinTable(name="entity_taxonomy_term",
	 *      joinColumns={@ORM\JoinColumn(name="taxonomy_term_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id")}
	 *      )
	 */
	protected $entities;
	
	public function __construct(){
		$this->children = new ArrayCollection();
	}
}