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

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Versioning\Entity\RepositoryInterface;
use Link\Entity\LinkEntityInterface;

/**
 * An entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity")
 */
class Entity extends AbstractEntity implements RepositoryInterface, LinkEntityInterface, EntityInterface
{

    /**
     * @ORM\ManyToMany(targetEntity="Entity", mappedBy="children")
     * @ORM\JoinTable(name="entity_link",
     * joinColumns={
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id")
     * },
     * inverseJoinColumns={
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * }
     * )
     */
    protected $parents;

    /**
     * @ORM\ManyToMany(targetEntity="Entity", mappedBy="parents")
     * @ORM\JoinTable(
     * name="entity_link",
     * joinColumns={
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id")
     * },
     * inverseJoinColumns={
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * }
     * )
     */
    protected $children;

    /**
     * @ORM\OneToMany(targetEntity="Revision", mappedBy="repository")
     */
    protected $revisions;

    /**
     * @ORM\OneToOne(targetEntity="Revision")
     * @ORM\JoinColumn(name="current_revision_id", referencedColumnName="id")
     */
    protected $currentRevision;

    /**
     * @ORM\ManyToMany(targetEntity="\Taxonomy\Entity\TermTaxonomy")
     * @ORM\JoinTable(name="term_taxonomy_entity",
     * inverseJoinColumns={@ORM\JoinColumn(name="term_taxonomy_id", referencedColumnName="id")},
     * joinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id")}
     * )
     */
    protected $terms;

    /**
     * @ORM\ManyToOne(targetEntity="Factory", inversedBy="entities")
     * @ORM\JoinColumn(name="entity_factory_id", referencedColumnName="id")
     */
    protected $factory;

    /**
     * @ORM\ManyToOne(targetEntity="Core\Entity\Language")
     */
    protected $language;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $killed;

    /**
     * @ORM\Column(type="text",length=255,unique=true)
     */
    protected $uuid;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $slug;

    /**
     * @return field_type $currentRevision
     */
    public function getCurrentRevision ()
    {
        return $this->currentRevision;
    }

	/**
     * @return field_type $factory
     */
    public function getFactory ()
    {
        return $this->factory;
    }

	/**
     * @return field_type $language
     */
    public function getLanguage ()
    {
        return $this->language;
    }

	/**
     * @return field_type $date
     */
    public function getDate ()
    {
        return $this->date;
    }

	/**
     * @return field_type $killed
     */
    public function getKilled ()
    {
        return $this->killed;
    }

	/**
     * @return field_type $slug
     */
    public function getSlug ()
    {
        return $this->slug;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $parents
     * @return $this
     */
    public function setParents ($parents)
    {
        $this->parents = $parents;
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
     * @param \Doctrine\Common\Collections\ArrayCollection $revisions
     * @return $this
     */
    public function setRevisions ($revisions)
    {
        $this->revisions = $revisions;
        return $this;
    }

	/**
     * @param field_type $currentRevision
     * @return $this
     */
    public function setCurrentRevision ($currentRevision)
    {
        $this->currentRevision = $currentRevision;
        return $this;
    }

	/**
     * @param field_type $factory
     * @return $this
     */
    public function setFactory ($factory)
    {
        $this->factory = $factory;
        return $this;
    }

	/**
     * @param field_type $language
     * @return $this
     */
    public function setLanguage ($language)
    {
        $this->language = $language;
        return $this;
    }

	/**
     * @param field_type $date
     * @return $this
     */
    public function setDate ($date)
    {
        $this->date = $date;
        return $this;
    }

	/**
     * @param field_type $killed
     * @return $this
     */
    public function setKilled ($killed)
    {
        $this->killed = $killed;
        return $this;
    }

	/**
     * @param field_type $slug
     * @return $this
     */
    public function setSlug ($slug)
    {
        $this->slug = $slug;
        return $this;
    }

	public function __construct()
    {
        $this->revisions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::addRevision()
     */
    public function addNewRevision()
    {
        $revision = new Revision();
        $revision->setRepository($this);
        return $revision;
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::getRevisions()
     */
    public function getRevisions()
    {
        return $this->revisions;
    }
    
    /*
     * (non-PHPdoc) @see \Link\Entity\LinkEntityInterface::getChildren()
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /*
     * (non-PHPdoc) @see \Link\Entity\LinkEntityInterface::getParents()
     */
    public function getParents()
    {
        return $this->parents;
    }
}