<?php

namespace Taxonomy\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="taxonomy")
 */
class Taxonomy extends AbstractEntity {
    /**
     * @ORM\OneToMany(targetEntity="TaxonomyTerms", mappedBy="taxonomy")
     **/
	protected $terms;

	/** @ORM\Column(type="text",length=255) */
	protected $name;
	
	public function __construct(){
		$this->terms = new ArrayCollection();
	}
}