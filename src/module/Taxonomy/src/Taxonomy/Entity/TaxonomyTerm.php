<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
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
class TaxonomyTerm extends AbstractEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy", inversedBy="terms")
     */
    protected $taxonomy;

    /**
     * @ORM\OneToMany(targetEntity="TaxonomyTerm", mappedBy="parent")
     */
    private $children;
    
    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyTerm", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="integer")
     */
    protected $order;

    /**
     * @ORM\Column(type="text",length=255,name="term")
     */
    protected $name;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $slug;

    /**
     * @ORM\ManyToMany(targetEntity="\Entity\Entity\Entity")
     * @ORM\JoinTable(name="entity_taxonomy_term",
     * joinColumns={@ORM\JoinColumn(name="taxonomy_term_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id")}
     * )
     */
    protected $entities;

    /**
     * @return field_type $taxonomy
     */
    public function getTaxonomy ()
    {
        return $this->taxonomy;
    }

	/**
     * @return \Doctrine\Common\Collections\ArrayCollection $children
     */
    public function getChildren ()
    {
        return $this->children;
    }

	/**
     * @return field_type $parent
     */
    public function getParent ()
    {
        return $this->parent;
    }

	/**
     * @return field_type $order
     */
    public function getOrder ()
    {
        return $this->order;
    }

	/**
     * @return field_type $name
     */
    public function getName ()
    {
        return $this->name;
    }

	/**
     * @return field_type $slug
     */
    public function getSlug ()
    {
        return $this->slug;
    }

	/**
     * @return field_type $entities
     */
    public function getEntities ()
    {
        return $this->entities;
    }

	/**
     * @param field_type $taxonomy
     * @return $this
     */
    public function setTaxonomy ($taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $children
     * @return $this
     */
    public function setChildren ($children)
    {
        $this->children = $children;
        return $this;
    }

	/**
     * @param field_type $parent
     * @return $this
     */
    public function setParent ($parent)
    {
        $this->parent = $parent;
        return $this;
    }

	/**
     * @param field_type $order
     * @return $this
     */
    public function setOrder ($order)
    {
        $this->order = $order;
        return $this;
    }

	/**
     * @param field_type $name
     * @return $this
     */
    public function setName ($name)
    {
        $this->name = $name;
        return $this;
    }

	/**
     * @param field_type $slug
     * @return $this
     */
    public function setSlug ($slug)
    {
        $this->slug = $slug;
        return $this;
    }

	/**
     * @param field_type $entities
     * @return $this
     */
    public function setEntities ($entities)
    {
        $this->entities = $entities;
        return $this;
    }

	public function __construct ()
    {
        $this->children = new ArrayCollection();
    }
}