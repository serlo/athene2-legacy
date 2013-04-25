<?php

namespace Taxonomy\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="taxonomy_terms")
 */
class TaxonomyTerms extends AbstractEntity {
	/**
	 * @ORM\ManyToOne(targetEntity="Taxonomy", inversedBy="terms")
	 **/
	protected $taxonomy;

	protected $parent;	
	protected $children;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Core\Entity\Language")
	 **/
	protected $language;
	
    /** @ORM\Column(type="integer") */
	protected $order;

	/** @ORM\Column(type="text",length=255) */
	protected $name;

	/** @ORM\Column(type="text",length=255) */
	protected $slug;
	
	public function __construct(){
		$this->children = new ArrayCollection();
	}
}