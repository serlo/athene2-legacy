<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Uuid\Entity\UuidEntity;
use Taxonomy\Exception\RuntimeException;

/**
 * A
 * Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="term_taxonomy")
 */
class TermTaxonomy extends UuidEntity implements TermTaxonomyEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy",
     * inversedBy="terms")
     */
    protected $taxonomy;

    /**
     * @ORM\ManyToOne(targetEntity="Term\Entity\Term",
     * inversedBy="termTaxonomies")
     */
    protected $term;

    /**
     * @ORM\OneToMany(targetEntity="TermTaxonomy",
     * mappedBy="parent")
     * @ORM\OrderBy({"weight"
     * =
     * "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="TermTaxonomy",
     * inversedBy="children")
     * @ORM\JoinColumn(name="parent_id",
     * referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="integer")
     */
    protected $weight;

    /**
     * @ORM\ManyToMany(targetEntity="\Entity\Entity\Entity")
     * @ORM\JoinTable(name="term_taxonomy_entity",
     * joinColumns={@ORM\JoinColumn(name="term_taxonomy_id",
     * referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="entity_id",
     * referencedColumnName="id")}
     * )
     */
    protected $entities;

    protected $allowedRelations = array(
        'entities'
    );
    
    /*
     *
     * (non-PHPdoc)
     * @see
     * \Taxonomy\Entity\TermTaxonomyEntityInterface::getDescription()
     */
    public function getDescription ()
    {
        // TODO
    // Auto-generated
    // method
    // stub
    }
    
    /*
     *
     * (non-PHPdoc)
     * @see
     * \Taxonomy\Entity\TermTaxonomyEntityInterface::hasParent()
     */
    public function hasParent ()
    {
        return (is_object($this->getParent()));
    }

    public function hasChildren ()
    {
        return $this->getChildren()->count() != 0;
    }
    
    /*
     *
     * (non-PHPdoc)
     * @see
     * \Taxonomy\Entity\TermTaxonomyEntityInterface::setDescription()
     */
    public function setDescription ($description)
    {
        // TODO
    // Auto-generated
    // method
    // stub
    }

    public function getFactory ()
    {
        return $this->getTaxonomy()->getFactory();
    }

    /**
     *
     * @return field_type
     *         $taxonomy
     */
    public function getTaxonomy ()
    {
        return $this->taxonomy;
    }

    public function countEntities ()
    {
        return $this->get('entities')->count();
    }

    /**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     *         $children
     */
    public function getChildren ()
    {
        return $this->children;
    }

    /**
     *
     * @return field_type
     *         $parent
     */
    public function getParent ()
    {
        return $this->parent;
    }

    /**
     *
     * @return field_type
     *         $name
     */
    public function getName ()
    {
        return $this->getTerm()->getName();
    }

    /**
     *
     * @return field_type
     *         $slug
     */
    public function getSlug ()
    {
        return $this->getTerm()->getSlug();
    }

    /**
     *
     * @param field_type $taxonomy            
     * @return $this
     */
    public function setTaxonomy ($taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    /**
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $children            
     * @return $this
     */
    public function setChildren ($children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     *
     * @param field_type $parent            
     * @return $this
     */
    public function setParent ($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     *
     * @return field_type
     *         $weight
     */
    public function getWeight ()
    {
        return $this->weight;
    }

    /**
     *
     * @param field_type $weight            
     * @return $this
     */
    public function setWeight ($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     *
     * @param field_type $name            
     * @return $this
     */
    public function setName ($name)
    {
        $this->getTerm()->setName($name);
        return $this;
    }

    /**
     *
     * @param field_type $slug            
     * @return $this
     */
    public function setSlug ($slug)
    {
        $this->getTerm()->setSlug($slug);
        return $this;
    }

    /**
     *
     * @return field_type
     *         $term
     */
    public function getTerm ()
    {
        return $this->term;
    }

    /**
     *
     * @param field_type $term            
     * @return $this
     */
    public function setTerm ($term)
    {
        $this->term = $term;
        return $this;
    }

    public function __construct ()
    {
        $this->children = new ArrayCollection();
    }

    public function getPath ()
    {
        $path = array();
        $term = $this;
        $exit = false;
        while (! $exit) {
            $exit = ! $term->hasParent();
            $path[] = $term->getSlug();
            if ($exit)
                break;
            $term = $term->getParent();
        }
        return array_reverse($path);
    }

    public function getArrayCopy ()
    {
        return array(
            'id' => $this->getId(),
            'term' => array(
                'name' => $this->getName()
            ),
            'taxonomy' => $this->getTaxonomy()->getId(),
            'parent' => $this->getParent()->getId()
        );
    }

    public function getRelations ($field)
    {
        if (in_array($field, $this->allowedRelations)) {
            return $this->$field;
        }
        throw new RuntimeException(sprintf('Field %s is not whitelisted.', $field));
    }
}