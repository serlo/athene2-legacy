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
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language")
     */
    protected $language;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyType", inversedBy="taxonomies")
     * @ORM\JoinColumn(name="taxonomy_type_id", referencedColumnName="id")
     */
    protected $type;

    /**
     *
     * @return field_type $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param field_type $type            
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection $terms
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $terms            
     * @return $this
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
        return $this;
    }

    public function __construct()
    {
        $this->terms = new ArrayCollection();
    }
    
    public function getName(){
        return $this->getType()->getName();
    }
}