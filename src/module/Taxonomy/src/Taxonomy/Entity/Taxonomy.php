<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="taxonomy")
 */
class Taxonomy extends AbstractEntity implements TaxonomyEntityInterface
{

    /**
     * @ORM\OneToMany(targetEntity="Taxonomy\Entity\TermTaxonomy", mappedBy="taxonomy")
     * @ORM\OrderBy({"weight" = "ASC"})
     */
    protected $terms;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyFactory", inversedBy="taxonomies")
     * @ORM\JoinColumn(name="taxonomy_factory_id", referencedColumnName="id")
     */
    protected $factory;

    /**
     * @ORM\ManyToOne(targetEntity="Subject\Entity\Subject", inversedBy="taxonomies")
     */
    protected $subject;

    /**
     * @return field_type $subject
     */
    public function getSubject ()
    {
        return $this->subject;
    }

	/**
     * @param field_type $subject
     * @return $this
     */
    public function setSubject ($subject)
    {
        $this->subject = $subject;
        return $this;
    }

	/**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection $terms
     */
    public function getTerms ()
    {
        return $this->terms;
    }

    /**
     *
     * @return field_type $name
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     *
     * @return field_type $factory
     */
    public function getFactory ()
    {
        return $this->factory;
    }

    /**
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $terms            
     * @return $this
     */
    public function setTerms ($terms)
    {
        $this->terms = $terms;
        return $this;
    }

    /**
     *
     * @param field_type $name            
     * @return $this
     */
    public function setName ($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param field_type $factory            
     * @return $this
     */
    public function setFactory ($factory)
    {
        $this->factory = $factory;
        return $this;
    }

    public function __construct ()
    {
        $this->terms = new ArrayCollection();
    }
}