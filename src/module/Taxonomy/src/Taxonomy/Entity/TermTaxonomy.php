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
 * @ORM\Table(name="term_taxonomy")
 */
class TermTaxonomy extends AbstractEntity implements TermTaxonomyEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy", inversedBy="terms")
     */
    protected $taxonomy;

    /**
     * @ORM\ManyToOne(targetEntity="Term\Entity\Term", inversedBy="termTaxonomies")
     */
    protected $term;

    /**
     * @ORM\OneToMany(targetEntity="TermTaxonomy", mappedBy="parent")
     */
    private $children;
    
    /**
     * @ORM\ManyToOne(targetEntity="TermTaxonomy", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="integer")
     */
    protected $order;

    /**
     * @ORM\ManyToMany(targetEntity="\Entity\Entity\Entity")
     * @ORM\JoinTable(name="entity_taxonomy_term",
     * joinColumns={@ORM\JoinColumn(name="taxonomy_term_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id")}
     * )
     */
    protected $entities;

    /* (non-PHPdoc)
     * @see \Taxonomy\Entity\TermTaxonomyEntityInterface::getDescription()
     */
    public function getDescription ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Entity\TermTaxonomyEntityInterface::hasParent()
     */
    public function hasParent ()
    {
        // TODO Auto-generated method stub
        
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\Entity\TermTaxonomyEntityInterface::setDescription()
     */
    public function setDescription ($description)
    {
        // TODO Auto-generated method stub
        
    }

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
        return $this->getTerm()->getName();
    }

	/**
     * @return field_type $slug
     */
    public function getSlug ()
    {
        return $this->getTerm()->getSlug();
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
        $this->getTerm()->setName($name);
        return $this;
    }

	/**
     * @param field_type $slug
     * @return $this
     */
    public function setSlug ($slug)
    {
        $this->getTerm()->setSlug($slug);
        return $this;
    }

	/**
     * @return field_type $term
     */
    public function getTerm ()
    {
        return $this->term;
    }

	/**
     * @param field_type $term
     * @return $this
     */
    public function setTerm ($term)
    {
        $this->term = $term;
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
    
    public function getPath(){
        $path = array();
        $term = $this;
        $exit = false;
        while(!$exit){
            $exit = !$term->hasParent();
            $path[] = $term->getSlug();
            if($exit) break;
            $term = $term->getParent();
        }
        return array_reverse($path);
    }
}