<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Uuid\Entity\UuidEntity;
use Taxonomy\Exception\RuntimeException;
use Common\ArrayCopyProvider;
use Entity\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * A
 * Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="term_taxonomy")
 */
class TermTaxonomy extends UuidEntity implements TermTaxonomyInterface, ArrayCopyProvider
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="termTaxonomy")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy",inversedBy="terms")
     */
    protected $taxonomy;

    /**
     * @ORM\ManyToOne(targetEntity="Term\Entity\Term",
     * inversedBy="termTaxonomies")
     */
    protected $term;

    /**
     * @ORM\OneToMany(targetEntity="TermTaxonomy",mappedBy="parent")
     * @ORM\OrderBy({"weight"="ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="TermTaxonomy",inversedBy="children")
     * @ORM\JoinColumn(name="parent_id",referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="integer")
     */
    protected $weight;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="Discussion\Entity\Comment", mappedBy="terms")
     * @ORM\JoinTable(name="term_taxonomy_comment",
     * joinColumns={@ORM\JoinColumn(name="term_taxonomy_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id")}
     * )
     */
    protected $comments;

    /**
     * @ORM\OneToMany(
     * targetEntity="TermTaxonomyEntity",
     * mappedBy="termTaxonomy",
     * cascade={"persist", "remove"},
     * orphanRemoval=true
     * )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $termTaxonomyEntities;

    protected $allowedRelations = array(
        'entities',
        'comments'
    );

    public function getDescription()
    {
        return $this->description;
    }

    public function hasParent()
    {
        return (is_object($this->getParent()));
    }

    public function hasChildren()
    {
        return $this->getChildren()->count() != 0;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getName()
    {
        return $this->getTerm()->getName();
    }

    public function getSlug()
    {
        return $this->getTerm()->getSlug();
    }

    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    public function getTerm()
    {
        return $this->term;
    }

    public function setTerm($term)
    {
        $this->term = $term;
        return $this;
    }

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->entities = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->termTaxonomyEntities = new ArrayCollection();
    }

    public function getArrayCopy()
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

    public function getAssociated($field)
    {
        if (in_array($field, $this->allowedRelations)) {
            if (property_exists($this, $field)) {
                return $this->$field;
            } else {
                $field = 'get' . ucfirst($field);
                return $this->$field();
            }
        }
        throw new RuntimeException(sprintf('Field %s is not whitelisted.', $field));
    }

    public function countAssociated($field)
    {
        return $this->getAssociated($field)->count();
    }

    public function addAssociation($field, $entity)
    {
        if (property_exists($this, $field)) {
            if(!$entity instanceof TermTaxonomyAware)
                throw new \Taxonomy\Exception\InvalidArgumentException(sprintf('Expected TermTaxonomyAware but got %s', get_class($entity)));
            $this->getAssociated($field)->add($entity);
            $entity->addTaxonomy($this);
        } else {
            $field = 'add' . ucfirst($field);
            $this->$field($entity);
        }
        return $this;
    }

    public function orderAssociated($association, $of, $order)
    {
        $method = 'order'.ucfirst($association);
        return $this->$method($of, $order);
    }
    
    protected function orderEntities($entity, $position){
        foreach ($this->termTaxonomyEntities as $rel) {
            if ($rel->getEntity()->getId() == $entity) {
                $rel->setPosition($position);
                break;
            }
        }
        return $rel;
    }

    public function removeAssociation($field, $entity)
    {
        if (property_exists($this, $field)) {
            if(!$entity instanceof TermTaxonomyAware)
                throw new \Taxonomy\Exception\InvalidArgumentException(sprintf('Expected TermTaxonomyAware but got %s', get_class($entity)));
            $this->getAssociated($field)->removeElement($entity);
            $entity->removeTaxonomy($this);
        } else {
            $field = 'remove' . ucfirst($field);
            $this->$field($entity);
        }
        return $this;
    }

    protected function addEntities(EntityInterface $entity)
    {
        // Build new relation object to handle join entity correct
        $rel = new TermTaxonomyEntity($this, $entity);
        
        // Add relation object to collection
        $this->termTaxonomyEntities->add($rel);
        $entity->addTaxonomyIndex($rel);
        
        return $this;
    }
    
    protected function removeEntities(EntityInterface $entity)
    {
        // Iterate over all join entities to find the correct
        foreach ($this->termTaxonomyEntities as $rel) {
            if ($rel->getEntity() === $entity) {
                $this->termTaxonomyEntities->removeElement($rel);
                $rel->getEntity()->removeTaxonomyIndex($rel);
                break;
            }
        }
        return $this;
    }
    
    protected function getEntities()
    {
        $collection = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($this->termTaxonomyEntities as $rel) {
            $collection->add($rel->getEntity());
        }
        
        return $collection;
    }

    public function getLanguage()
    {
        return $this->getTaxonomy()->getLanguage();
    }
}