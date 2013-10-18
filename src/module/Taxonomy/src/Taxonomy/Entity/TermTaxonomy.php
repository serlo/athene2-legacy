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
use Common\ArrayCopyProvider;

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
     * @ORM\ManyToMany(targetEntity="\Entity\Entity\Entity", mappedBy="terms")
     * @ORM\JoinTable(name="term_taxonomy_entity",
     *      joinColumns={@ORM\JoinColumn(name="term_taxonomy_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id")}
     * )
     */
    protected $entities;

    protected $allowedRelations = array(
        'entities'
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
            return $this->$field;
        }
        throw new RuntimeException(sprintf('Field %s is not whitelisted.', $field));
    }

    public function countAssociated($field)
    {
        return $this->getAssociated($field)->count();
    }

    public function addAssociation($field, TermTaxonomyAware $entity)
    {
        $this->getAssociated($field)->add($entity);
        $entity->addTermTaxonomy($this);
        return $this;
    }

    public function removeAssociation($field, TermTaxonomyAware $entity)
    {
        $this->getAssociated($field)->removeElement($entity);
        $entity->removeTermTaxonomy($this);
        return $this;
    }

    public function getLanguage()
    {
        return $this->getTaxonomy()->getLanguage();
    }
}