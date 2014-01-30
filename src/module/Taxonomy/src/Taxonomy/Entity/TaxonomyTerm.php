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
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Entity\Entity\EntityInterface;
use Taxonomy\Exception\RuntimeException;
use Taxonomy\Exception;
use Term\Entity\TermEntityInterface;
use Uuid\Entity\UuidEntity;

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
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="taxonomyTerm", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy\Entity\Taxonomy",inversedBy="terms")
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
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyTerm",inversedBy="children")
     * @ORM\JoinColumn(name="parent_id",referencedColumnName="id")
     */
    protected $parent;

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
    protected $blogPosts;

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
        $this->weight = 0;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function getInstance()
    {
        return $this->getTaxonomy()->getInstance();
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

    public function setParent(TaxonomyTermInterface $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function setPosition($position)
    {
        $this->weight = $position;
        return $this;
    }

    public function setTerm(TermEntityInterface $term)
    {
        $this->term = $term;
        return $this;
    }

    public function hasParent()
    {
        return (is_object($this->getParent()));
    }

    public function hasChildren()
    {
        return $this->getChildren()->count() !== 0;
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

    public function findChildrenByTaxonomyNames(array $names)
    {
        return $this->getChildren()->filter(function (TaxonomyTermInterface $term) use($names)
        {
            return in_array($term->getTaxonomy()
                ->getName(), $names);
        });
    }

    public function findChildBySlugs(array $slugs)
    {
        $slug = array_shift($slugs);
        
        foreach ($this->getChildren() as $child) {
            if ($child->getSlug() == $slug) {
                return $child->findChildBySlugs($slugs);
            }
        }
        return $this;
    }

    public function isAssociated($association, TaxonomyTermAwareInterface $object)
    {
        $associations = $this->getAssociated($association);
        return $associations->contains($object);
    }

    public function countAssociations($field)
    {
        return $this->getAssociated($field)->count();
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

    public function associateObject($field, TaxonomyTermAwareInterface $entity)
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
        
        if (! method_exists($this, $method)) {
            throw new Exception\RuntimeException(sprintf('Association `%s` does not support sorting. You\'d have to implement a node', $association));
        }
        
        return $this->$method($objectId, $order);
    }

    public function removeAssociation($field, TaxonomyTermAwareInterface $entity)
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

    public function getAssociatedRecursive($associations, array $allowedTaxonomies = [])
    {
        $collection = new ArrayCollection();
        
        $this->iterAssociationNodes($collection, $this, $associations, $allowedTaxonomies);
        
        return $collection;
    }

    public function knowsAncestor(TaxonomyTermInterface $ancestor)
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

    public function slugify($stopAtType = NULL, $delimiter = '/')
    {
        return substr($this->processSlugs($this, $stopAtType, $delimiter), 0, - 1);
    }

    /**
     *
     * @param TaxonomyTermInterface $term            
     * @param string $parent            
     * @return string
     */
    protected function processSlugs(TaxonomyTermInterface $term, $stopAtType, $delimiter)
    {
        $slug = '';
        if ($term->getTaxonomy()->getName() != $stopAtType) {
            if ($term->hasParent()) {
                $slug = $this->processSlugs($term->getParent(), $stopAtType, $delimiter);
            }
            $slug .= $term->getSlug() . $delimiter;
        }
        return $slug;
    }

    /**
     *
     * @return ArrayCollection TaxonomyTermNodeInterface[]
     */
    protected function getEntityNodes()
    {
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

    protected function iterAssociationNodes(Collection $collection, TaxonomyTermInterface $term, $associations, array $allowedTaxonomies)
    {
        foreach ($term->getAssociated($associations) as $link) {
            $collection->add($link);
        }
        
        foreach ($term->getChildren() as $child) {
            if (empty($allowedTaxonomies) || in_array($child->getTaxonomy()->getName(), $allowedTaxonomies)) {
                $this->iterAssociationNodes($collection, $child, $associations, $allowedTaxonomies);
            }
        }
    }
}