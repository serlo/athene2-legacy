<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Taxonomy\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Uuid\Entity\UuidEntity;
use Taxonomy\Exception\RuntimeException;
use Entity\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Blog\Entity\PostInterface;
use Taxonomy\Model\TaxonomyTermModelInterface;
use Term\Model\TermModelInterface;
use Taxonomy\Model\TaxonomyTermModelAwareInterface;
use Taxonomy\Model\TaxonomyTermNodeModelInterface;

/**
 * A
 * Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="term_taxonomy")
 */
class TaxonomyTerm extends UuidEntity implements TaxonomyTermInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="taxonomyTerm")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy",inversedBy="terms")
     */
    protected $taxonomy;

    /**
     * @ORM\ManyToOne(targetEntity="Term\Entity\TermEntity",
     * inversedBy="termTaxonomies")
     */
    protected $term;

    /**
     * @ORM\OneToMany(targetEntity="TaxonomyTerm",mappedBy="parent")
     * @ORM\OrderBy({"weight"="ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyTerm",inversedBy="children")
     * @ORM\JoinColumn(name="parent_id",referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="integer")
     */
    protected $weight;

    /**
     * @ORM\Column(type="string", nullable=true)
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
     * targetEntity="TaxonomyTermEntity",
     * mappedBy="termTaxonomy",
     * cascade={"persist", "remove"},
     * orphanRemoval=true
     * )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $termTaxonomyEntities;

    /**
     * @ORM\OneToMany(targetEntity="Blog\Entity\Post",mappedBy="category")
     * @ORM\OrderBy({"id"="DESC"})
     */
    private $blogPosts;
    
    protected $allowedRelations = [
            'entities',
            'comments',
            'blogPosts'
        ];

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->entities = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->termTaxonomyEntities = new ArrayCollection();
    }

    public function getEntity()
    {
        return $this;
    }

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
        return $this->getChildren()->count() !== 0;
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

    public function getType()
    {
        return $this->getTaxonomy()->getType();
    }

    public function getName()
    {
        return $this->getTerm()->getName();
    }

    public function getSlug()
    {
        return $this->getTerm()->getSlug();
    }

    public function getPosition()
    {
        return $this->weight;
    }

    public function getTerm()
    {
        return $this->term;
    }

    public function findAncestorByTypeName($name)
    {
        $term = $this;
        while ($term->hasParent()) {
            $term = $term->getParent();
            if ($term->getTaxonomy()->getName() === $name) {
                return $term;
            }
        }
        return NULL;
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

    public function isAssociated($association, TaxonomyTermModelAwareInterface $object)
    {
        $associations = $this->getEntity()->getAssociated($association);
        return $associations->contains($object);
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

    public function countAssociations($field)
    {
        return $this->getAssociated($field)->count();
    }

    public function getLanguage()
    {
        return $this->getTaxonomy()->getLanguage();
    }

    public function associateObject($field, TaxonomyTermModelAwareInterface $entity)
    {
        $method = 'add' . ucfirst($field);
        if (! method_exists($this, $method)) {
            $this->getAssociated($field)->add($entity);
            $entity->addTaxonomyTerm($this);
        } else {
            $this->$method($entity);
        }
        return $this;
    }

    public function positionAssociatedObject($association, $objectId, $order)
    {
        $method = 'order' . ucfirst($association);
        
        if (! method_exists($this, $method))
            throw new \Taxonomy\Exception\RuntimeException(sprintf('Association `%s` does not support sorting. You\'d have to implement a node', $association));
        
        return $this->$method($objectId, $order);
    }

    public function removeAssociation($field, TaxonomyTermModelAwareInterface $entity)
    {
        $method = 'remove' . ucfirst($field);
        if (! method_exists($this, $method)) {
            $this->getAssociated($field)->removeElement($entity);
            $entity->removeTaxonomyTerm($this);
        } else {
            $this->$method($entity);
        }
        return $this;
    }

    public function setTaxonomy(TaxonomyInterface $taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setParent(TaxonomyTermModelInterface $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function setPosition($position)
    {
        $this->weight = $position;
        return $this;
    }

    public function setTerm(TermModelInterface $term)
    {
        $this->term = $term;
        return $this;
    }

    public function knowsAncestor(TaxonomyTermModelInterface $ancestor)
    {
        $term = $this;
        while ($term->hasParent()) {
            $term = $term->getParent();
            if ($term === $ancestor) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 
     * @return ArrayCollection|TaxonomyTermNodeModelInterface[]
     */
    protected function getEntityNodes() {
        return $this->termTaxonomyEntities;
    }

    protected function orderEntities($objectId, $position)
    {
        foreach ($this->getEntityNodes() as $rel) {
            if ($rel->getObject()->getId() === (int) $objectId) {
                $rel->setPosition($position);
                break;
            }
        }
        return $rel;
    }

    protected function addEntities(EntityInterface $entity)
    {
        // Build new relation object to handle join entity correct
        $rel = new TaxonomyTermEntity($this, $entity);
        
        // Add relation object to collection
        $this->getEntityNodes()->add($rel);
        $entity->addTaxonomyTerm($this, $rel);
        
        return $this;
    }

    protected function removeEntities(EntityInterface $entity)
    {
        // Iterate over all join entities to find the correct
        foreach ($this->getEntityNodes() as $rel) {
            if ($rel->getObject() === $entity) {
                $rel->removeElement($rel);
                $rel->getObject()->removeTaxonomyTerm($this, $rel);
                break;
            }
        }
        return $this;
    }

    protected function getEntities()
    {
        $collection = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($this->getEntityNodes() as $rel) {
            $collection->add($rel->getObject());
        }
        
        return $collection;
    }
}