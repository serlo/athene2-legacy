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
use Link\Entity\LinkableInterface;
use Uuid\Entity\UuidEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Versioning\Entity\RevisionInterface;
use Language\Entity\LanguageInterface;
use Link\Entity\LinkTypeInterface;
use Link\Entity\LinkInterface;
use Entity\Exception;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Entity\TaxonomyTermEntity;

/**
 * An entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity")
 */
class Entity extends UuidEntity implements RepositoryInterface, LinkableInterface, EntityInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="entity")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="EntityLink", mappedBy="child", cascade={"persist", "remove"},
     * orphanRemoval=true)
     * @ORM\OrderBy({"order" = "ASC"})
     */
    protected $parentLinks;

    /**
     * @ORM\OneToMany(targetEntity="EntityLink", mappedBy="parent", cascade={"persist", "remove"},
     * orphanRemoval=true)
     * @ORM\OrderBy({"order" = "ASC"})
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
     * targetEntity="Taxonomy\Entity\TaxonomyTermEntity",
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

    /**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|LinkInterface
     */
    public function getParentLinks()
    {
        return $this->parentLinks;
    }

    /**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|LinkInterface
     */
    public function getChildLinks()
    {
        return $this->childLinks;
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

    public function getTimestamp()
    {
        return $this->date;
    }

    public function getRevisions()
    {
        return $this->revisions;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setCurrentRevision(RevisionInterface $currentRevision)
    {
        $this->currentRevision = $currentRevision;
        return $this;
    }

    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;
        return $this;
    }

    public function setTimestamp(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    public function newRevision()
    {
        $revision = new Revision();
        $revision->setRepository($this);
        return $revision;
    }

    public function getTerms()
    {
        $collection = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($this->termTaxonomyEntities as $rel) {
            $collection->add($rel->getTaxonomyTerm());
        }
        
        return $collection;
    }

    public function getChildren(LinkTypeInterface $type)
    {
        $collection = new ArrayCollection();
        
        foreach ($this->getChildLinks() as $link) {
            if ($link->getType() === $type) {
                $collection->add($link->getChild());
            }
        }
        
        return $collection;
    }

    public function getParents(LinkTypeInterface $type)
    {
        $collection = new ArrayCollection();
        
        foreach ($this->getChildLinks() as $link) {
            if ($link->getType() === $type) {
                $collection->add($link->getParent());
            }
        }
        
        return $collection;
    }

    public function positionChild(LinkableInterface $child, LinkTypeInterface $type, $position)
    {
        $link = $this->findChildLink($child, $type);
        $link->setPosition($position);
        return $link;
    }

    public function positionParent(LinkableInterface $parent, LinkTypeInterface $type, $position)
    {
        $link = $this->findParentLink($parent, $type);
        $link->setPosition($position);
        return $link;
    }
    
    public function removeChildLink(LinkInterface $link){
        $this->getChildLinks()->removeElement($link);   
        return $this;     
    }
    
    public function removeParentLink(LinkInterface $link){
        $this->getParentLinks()->removeElement($link);
        return $this;
    }

    public function removeChild(LinkableInterface $child, LinkTypeInterface $type)
    {
        $link = $this->findChildLink($child, $type);
        $this->removeChildLink($link);
        $child->removeParentLink($link);
        return $this;
    }

    public function removeParent(LinkableInterface $parent, LinkTypeInterface $type)
    {
        $link = $this->findParentLink($parent, $type);
        $this->removeParentLink($link);
        $parent->removeChildLink($link);
        return $this;
    }

    public function addChild(LinkableInterface $child, LinkTypeInterface $type, $order = -1)
    {
        if ($order == - 1) {
            $order = $this->getLinkOrderOffset($child, $type, 'child') + 1;
        }
        $link = new EntityLink($type, $order);
        $link->setParent($this);
        $link->setChild($child);
        $this->getChildLinks()->add($link);
        $child->getParentLinks()->add($link);
        return $this;
    }

    public function addParent(LinkableInterface $parent, LinkTypeInterface $type, $order = -1)
    {
        if ($order == - 1) {
            $order = $this->getLinkOrderOffset($parent, $type, 'parent') + 1;
        }
        $link = new EntityLink($type, $order);
        $link->setParent($parent);
        $link->setChild($this);
        $this->getParentLinks()->add($link);
        $parent->getChildLinks()->add($link);
        return $this;
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

    public function addTaxonomyIndex(TaxonomyTermEntity $taxonomy)
    {
        $this->termTaxonomyEntities->add($taxonomy);
        return $this;
    }

    public function removeTaxonomyIndex(TaxonomyTermEntity $taxonomy)
    {
        $this->termTaxonomyEntities->removeElement($taxonomy);
        return $this;
    }
    
    protected function findParentLink(LinkableInterface $parent, LinkTypeInterface $type)
    {
        foreach($this->getParentLinks() as $link){
            if($link->getParent() === $parent && $link->getType() === $type){
                return $link;
            }
        }
        throw new Exception\RuntimeException(sprintf('`%s` is not a `%s` child of `%s`.', $this->getId(), $type->getName(),  $parent->getId()));
    }
    
    protected function findChildLink(LinkableInterface $child, LinkTypeInterface $type)
    {
        foreach($this->getChildLinks() as $link){
            if($link->getChild() === $child && $link->getType() === $type){
                return $link;
            }
        }
        throw new Exception\RuntimeException(sprintf('`%s` is not a `%s` parent of `%s`.', $this->getId(), $type->getName(),  $child->getId()));
    }
    
    protected function findParentLinks(LinkTypeInterface $type)
    {
        $links = new ArrayCollection();
        foreach($this->getParentLinks() as $link){
            if($link->getType() === $type){
                $links->add($link);
            }
        }
        return $links;
    }
    
    protected function findChildLinks(LinkTypeInterface $type)
    {
        $links = new ArrayCollection();
        foreach($this->getChildLinks() as $link){
            if($link->getType() === $type){
                $links->add($link);
            }
        }
        return $links;
    }

    protected function getLinkOrderOffset(LinkableInterface $link, LinkTypeInterface $type, $field)
    {
        $method = 'find'.ucfirst($field).'Links';
        $e = $this->$method($type)->last();
        
        if (! is_object($e)) {
            return 0;
        } else {
            return $e->getPosition();
        }
    }
}