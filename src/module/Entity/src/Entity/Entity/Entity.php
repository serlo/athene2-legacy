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
use Uuid\Entity\UuidEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Versioning\Entity\RevisionInterface;
use Entity\Exception;
use License\Entity\LicenseInterface;
use Language\Entity\LanguageInterface;
use Doctrine\Common\Collections\Criteria;
use Entity\Options\EntityOptions;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Entity\TaxonomyTermNodeInterface;

/**
 * An entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity")
 */
class Entity extends UuidEntity implements EntityInterface
{
    use\Type\Entity\TypeAwareTrait;

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="entity", fetch="EXTRA_LAZY")
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
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language", inversedBy="entities")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /**
     * @ORM\ManyToOne(targetEntity="License\Entity\LicenseInterface")
     */
    protected $license;

    /**
     *
     * @var EntityOptions
     */
    protected $options;

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
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getLicense()
    {
        return $this->license;
    }

    public function getCurrentRevision()
    {
        return $this->currentRevision;
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

    public function getRevision($id)
    {
        return $this->revisions->matching(Criteria::create(Criteria::expr()->eq('id', $id)))
            ->current();
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

    public function createRevision()
    {
        $revision = new Revision();
        $revision->setRepository($this);
        return $revision;
    }

    public function getTaxonomyTerms()
    {
        $collection = new ArrayCollection();
        
        foreach ($this->termTaxonomyEntities as $rel) {
            $collection->add($rel->getTaxonomyTerm());
        }
        
        return $collection;
    }

    public function getChildren($linkyType, $childType = NULL)
    {
        $collection = new ArrayCollection();
        
        foreach ($this->getChildLinks() as $link) {
            $childTypeName = $link->getChild()
                ->getType()
                ->getName();
            if ($link->getType()->getName() === $linkyType && ($childType === NULL || ($childType !== NULL && $childTypeName === $childType))) {
                $collection->add($link->getChild());
            }
        }
        
        return $collection;
    }

    public function getParents($linkyType, $parentType = NULL)
    {
        $collection = new ArrayCollection();
        
        foreach ($this->getParentLinks() as $link) {
            $childTypeName = $link->getChild()
                ->getType()
                ->getName();
            if ($link->getType()->getName() === $linkyType && ($parentType === NULL || ($parentType !== NULL && $childTypeName === $parentType))) {
                $collection->add($link->getParent());
            }
        }
        
        return $collection;
    }

    public function setLicense(LicenseInterface $license)
    {
        $this->license = $license;
        return $this;
    }

    public function createLink()
    {
        return new EntityLink();
    }

    public function setOptions(EntityOptions $options)
    {
        $this->options = $options;
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

    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    public function addTaxonomyTerm(TaxonomyTermInterface $taxonomyTerm, TaxonomyTermNodeInterface $node = NULL)
    {
        if ($node === NULL) {
            throw new Exception\InvalidArgumentException('Missing parameter node');
        }
        $this->termTaxonomyEntities->add($node);
    }

    public function removeTaxonomyTerm(TaxonomyTermInterface $taxonomyTerm, TaxonomyTermNodeInterface $node = NULL)
    {
        if ($node === NULL) {
            throw new Exception\InvalidArgumentException('Missing parameter node');
        }
        $this->termTaxonomyEntities->removeElement($node);
    }

    public function getParentLinks()
    {
        return $this->parentLinks;
    }

    public function getChildLinks()
    {
        return $this->childLinks;
    }

    public function isUnrevised()
    {
        return ! $this->hasCurrentRevision() || ($this->hasCurrentRevision() && $this->getHead() !== $this->getCurrentRevision());
    }

    public function getHead()
    {
        return $this->revisions->first();
    }
}