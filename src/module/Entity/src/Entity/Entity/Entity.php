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
namespace Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Versioning\Entity\RepositoryInterface;
use Link\Entity\LinkEntityInterface;
use Uuid\Entity\UuidEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;
use Versioning\Entity\RevisionInterface;
use Taxonomy\Entity\TermTaxonomyAware;

/**
 * An entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity")
 */
class Entity extends UuidEntity implements RepositoryInterface, LinkEntityInterface, EntityInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="entity")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="EntityLink", mappedBy="child", cascade={"persist"})
     */
    protected $parentLinks;

    /**
     * @ORM\OneToMany(targetEntity="EntityLink", mappedBy="parent", cascade={"persist"})
     */
    protected $childLinks;

    /**
     * @ORM\OneToMany(targetEntity="Revision", mappedBy="repository")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $revisions;

    /**
     * @ORM\OneToOne(targetEntity="Revision")
     * @ORM\JoinColumn(name="current_revision_id", referencedColumnName="id")
     */
    protected $currentRevision;

    /**
     * @ORM\OneToMany(
     * targetEntity="Taxonomy\Entity\TermTaxonomyEntity",
     * mappedBy="entity",
     * cascade={"persist", "remove"},
     * orphanRemoval=true
     * )
     */
    protected $termTaxonomyEntities;

    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="entities")
     * @ORM\JoinColumn(name="entity_type_id", referencedColumnName="id")
     */
    protected $type;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language", inversedBy="entities")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    protected $childCollection;

    protected $parentCollection;

    public function getTermTaxonomies()
    {
        return $this->getTerms();
    }

    public function getTerms()
    {
        $collection = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($this->termTaxonomyEntities as $rel) {
            $collection->add($rel->getTermTaxonomy());
        }
        
        return $collection;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getCurrentRevision()
    {
        return $this->currentRevision;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setCurrentRevision(RevisionInterface $currentRevision)
    {
        $this->currentRevision = $currentRevision;
        return $this;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function __construct()
    {
        $this->revisions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->childLinks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parentLinks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->issues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->terms = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termTaxonomyEntities = new ArrayCollection();
        $this->fieldOrder = array();
    }

    public function newRevision()
    {
        $revision = new Revision();
        $revision->setRepository($this);
        return $revision;
    }

    public function getRevisions()
    {
        return $this->revisions;
    }

    public function getChildren(\Link\Entity\LinkTypeInterface $type)
    {
        if (! $this->childCollection)
            $this->childCollection = new ArrayCollection();
        
        foreach ($this->getChildLinks() as $link) {
            $child = $link->getChild();
            if (! $this->childCollection->containsKey($child->getId())) {
                $this->childCollection->set($child->getId(), $child);
            }
        }
        
        return $this->childCollection;
    }

    public function getParents(\Link\Entity\LinkTypeInterface $type)
    {
        if (! $this->parentCollection)
            $this->parentCollection = new ArrayCollection();
        
        foreach ($this->getParentLinks() as $link) {
            $child = $link->getParent();
            if (! $this->parentCollection->containsKey($child->getId())) {
                $this->parentCollection->set($child->getId(), $child);
            }
        }
        
        return $this->parentCollection;
    }

    public function getParentLinks()
    {
        return $this->parentLinks;
    }

    public function getChildLinks()
    {
        return $this->childLinks;
    }

    public function addChild(\Link\Entity\LinkEntityInterface $child, \Link\Entity\LinkTypeInterface $type, $order = -1)
    {
        if ($order == - 1) {
            $order = $this->getLinkOrderOffset($this->getChildLinks(), $child, $type, 'child') + 1;
        }
        $link = $this->createLink($type, $order);
        $link->setParent($this);
        $link->setChild($child);
        $this->getChildLinks()->add($link);
        $child->getParentLinks()->add($link);
        return $this;
    }

    public function addParent(\Link\Entity\LinkEntityInterface $parent, \Link\Entity\LinkTypeInterface $type, $order = -1)
    {
        if ($order == - 1) {
            $order = $this->getLinkOrderOffset($this->getParentLinks(), $parent, $type, 'parent') + 1;
        }
        $link = $this->createLink($type, $order);
        $link->setParent($parent);
        $link->setChild($this);
        $this->getParentLinks()->add($link);
        $parent->getChildLinks()->add($link);
        return $this;
    }

    protected function createLink(\Link\Entity\LinkTypeInterface $type, $order = -1)
    {
        $e = new EntityLink();
        $e->setOrder($order);
        $e->setType($type);
        return $e;
    }

    protected function getLinkOrderOffset(PersistentCollection $collection,\Link\Entity\LinkEntityInterface $parent, \Link\Entity\LinkTypeInterface $type, $field)
    {
        $e = $collection->matching(Criteria::create(Criteria::expr()->andX(Criteria::expr()->eq($field, $parent->getId()), Criteria::expr()->eq('type', $type->getId()))))
            ->last();
        if (! is_object($e)) {
            return 0;
        } else {
            return $e->getOrder();
        }
    }

    public function hasCurrentRevision()
    {
        return is_object($this->getCurrentRevision());
    }

    public function addRevision(RevisionInterface $revision)
    {
        $this->revisions->add($revision);
        return $this;
    }

    public function removeRevision(RevisionInterface $revision)
    {
        $this->revisions->removeElement($revision);
        return $this;
    }
    
    public function removeChild(\Link\Entity\LinkEntityInterface $parent, \Link\Entity\LinkTypeInterface $type)
    {
        // TODO Auto-generated method stub
    }
    
    public function removeParent(\Link\Entity\LinkEntityInterface $parent, \Link\Entity\LinkTypeInterface $type)
    {
        // TODO Auto-generated method stub
    }
    
    public function addTaxonomyIndex(\Taxonomy\Entity\TermTaxonomyEntity $taxonomy)
    {
        $this->termTaxonomyEntities->add($taxonomy);
        return $this;
    }
    
    public function removeTaxonomyIndex(\Taxonomy\Entity\TermTaxonomyEntity $taxonomy)
    {
        $this->termTaxonomyEntities->removeElement($taxonomy);
        return $this;
    }
}