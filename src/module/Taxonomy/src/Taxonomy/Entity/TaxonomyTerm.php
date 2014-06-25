<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Taxonomy\Entity;

use Blog\Entity\PostInterface;
use Discussion\Entity\CommentInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Entity\Entity\EntityInterface;
use Taxonomy\Exception\RuntimeException;
use Taxonomy\Exception;
use Term\Entity\TermEntityInterface;
use Uuid\Entity\Uuid;

/**
 * A
 * Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="term_taxonomy")
 */
class TaxonomyTerm extends Uuid implements TaxonomyTermInterface
{

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
     * @ORM\OneToMany(targetEntity="TaxonomyTerm", mappedBy="parent", cascade={"remove"}, orphanRemoval=true)
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
     * @ORM\OrderBy({"id"="DESC"})
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
     * @ORM\OneToMany(targetEntity="Blog\Entity\Post",mappedBy="blog")
     * @ORM\OrderBy({"publish"="DESC"})
     */
    protected $blogPosts;

    protected $allowedRelations = [
        'comments',
        'entities' => 'termTaxonomyEntities',
        'termTaxonomyEntities',
        'blogPosts'
    ];

    public function __construct()
    {
        $this->children             = new ArrayCollection();
        $this->entities             = new ArrayCollection();
        $this->blogPosts            = new ArrayCollection();
        $this->comments             = new ArrayCollection();
        $this->termTaxonomyEntities = new ArrayCollection();
        $this->weight               = 0;
    }

    public function associateObject(TaxonomyTermAwareInterface $entity)
    {
        $field  = $this->getAssociationFieldName($entity);
        $method = 'add' . ucfirst($field);
        if (!method_exists($this, $method)) {
            $this->getAssociated($field)->add($entity);
            $entity->addTaxonomyTerm($this);
        } else {
            $this->$method($entity);
        }
    }

    public function countAssociations($field)
    {
        return $this->getAssociated($field)->count();
    }

    public function countElements()
    {
        $count = 0;
        foreach ($this->allowedRelations as $elements) {
            $count += $this->$elements->count();
        }

        return $count;
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

        throw new Exception\TermNotFoundException();
    }

    public function findChildrenByTaxonomyNames(array $names)
    {
        return $this->getChildren()->filter(
            function (TaxonomyTermInterface $term) use ($names) {
                return in_array(
                    $term->getTaxonomy()->getName(),
                    $names
                );
            }
        );
    }

    /**
     * @param bool $trashed
     * @return Collection|TaxonomyTermInterface[]
     */
    public function findChildrenByTrashed($trashed)
    {
        return $this->getChildren()->filter(
            function (TaxonomyTermInterface $t) use ($trashed) {
                return $t->isTrashed() == $trashed;
            }
        );
    }

    public function getAssociated($field)
    {
        if (!in_array($field, $this->allowedRelations) && !isset($this->allowedRelations[$field])) {
            throw new RuntimeException(sprintf('Field %s is not whitelisted.', $field));
        }

        if (property_exists($this, $field)) {
            return $this->$field;
        } else {
            $field = 'get' . ucfirst($field);
            return $this->$field();
        }
    }

    public function getAssociatedRecursive($association, array $allowedTaxonomies = [])
    {
        $collection = new ArrayCollection();
        $this->iterAssociationNodes($collection, $this, $association, $allowedTaxonomies);
        return $collection;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getInstance()
    {
        return $this->getTaxonomy()->getInstance();
    }

    public function getName()
    {
        return $this->getTerm()->getName();
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(TaxonomyTermInterface $parent = null)
    {
        if (!$parent) {
            return;
        }
        $this->parent = $parent;
        $parent->getChildren()->add($this);
    }

    public function getPosition()
    {
        return $this->weight;
    }

    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    public function setTaxonomy(TaxonomyInterface $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    public function getTerm()
    {
        return $this->term;
    }

    public function setTerm(TermEntityInterface $term)
    {
        $this->term = $term;
    }

    public function getType()
    {
        return $this->getTaxonomy()->getType();
    }

    public function hasChildren()
    {
        return $this->getChildren()->count() !== 0;
    }

    public function hasParent()
    {
        return (is_object($this->getParent()));
    }

    public function isAssociated(TaxonomyTermAwareInterface $object)
    {
        $field        = $this->getAssociationFieldName($object);
        $associations = $this->getAssociated($field);
        return $associations->contains($object);
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

    public function positionAssociatedObject($object, $order, $association = null)
    {
        if (!$association) {
            $association = $this->getAssociationFieldName($object);
        }
        $method = 'order' . ucfirst($association);

        if (!method_exists($this, $method)) {
            throw new Exception\RuntimeException(sprintf(
                'Association `%s` does not support sorting. You\'d have to implement a node',
                $association
            ));
        }

        return $this->$method($object, $order);
    }

    public function removeAssociation(TaxonomyTermAwareInterface $entity)
    {
        $field  = $this->getAssociationFieldName($entity);
        $method = 'remove' . ucfirst($field);
        if (!method_exists($this, $method)) {
            $this->getAssociated($field)->removeElement($entity);
            $entity->removeTaxonomyTerm($this);
        } else {
            $this->$method($entity);
        }
    }

    public function setPosition($position)
    {
        $this->weight = $position;
    }

    public function slugify($stopAtType = null, $delimiter = '/')
    {
        return substr($this->processSlugs($this, $stopAtType, $delimiter), 0, -1);
    }

    protected function addEntities(EntityInterface $entity)
    {
        // Build new relation object to handle join entity correct
        $rel = new TaxonomyTermEntity($this, $entity);

        // Add relation object to collection
        $this->getEntityNodes()->add($rel);
        $entity->addTaxonomyTerm($this, $rel);
    }

    protected function getAssociationFieldName(TaxonomyTermAwareInterface $object)
    {
        if ($object instanceof EntityInterface) {
            return 'entities';
        } elseif ($object instanceof CommentInterface) {
            return 'comments';
        } elseif ($object instanceof PostInterface) {
            return 'blogPosts';
        } else {
            throw new RuntimeException(sprintf('Could not determine which field to use for %s', get_class($object)));
        }
    }

    protected function getEntities()
    {
        $collection = new ArrayCollection();

        foreach ($this->getEntityNodes() as $rel) {
            $collection->add($rel->getObject());
        }

        return $collection;
    }

    /**
     * @return ArrayCollection TaxonomyTermNodeInterface[]
     */
    protected function getEntityNodes()
    {
        return $this->termTaxonomyEntities;
    }

    protected function iterAssociationNodes(
        Collection $collection,
        TaxonomyTermInterface $term,
        $associations,
        array $allowedTaxonomies
    ) {
        foreach ($term->getAssociated($associations) as $link) {
            $collection->add($link);
        }

        foreach ($term->getChildren() as $child) {
            if (empty($allowedTaxonomies) || in_array($child->getTaxonomy()->getName(), $allowedTaxonomies)) {
                $this->iterAssociationNodes($collection, $child, $associations, $allowedTaxonomies);
            }
        }
    }

    protected function orderEntities($object, $position)
    {
        if ($object instanceof TaxonomyTermAwareInterface) {
            $id = $object->getId();
        } else {
            $id = (int)$object;
        }

        foreach ($this->getEntityNodes() as $rel) {
            if ($rel->getObject()->getId() === $id) {
                $rel->setPosition($position);
                break;
            }
        }

        return $rel;
    }

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

    protected function removeEntities(EntityInterface $entity)
    {
        // Iterate over all join entities to find the correct
        foreach ($this->getEntityNodes() as $rel) {
            if ($rel->getObject() === $entity) {
                $this->getEntityNodes()->removeElement($rel);
                $rel->getObject()->removeTaxonomyTerm($this, $rel);
                break;
            }
        }
    }
}
