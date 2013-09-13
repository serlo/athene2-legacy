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
 * A
 * Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="taxonomy_type")
 */
class TaxonomyType
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Taxonomy",
     * mappedBy="factory")
     */
    protected $taxonomies;

    /**
     * @ORM\Column(type="text",length=45)
     */
    protected $name;

    /**
     *
     * @return field_type
     *         $id
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     *
     * @param field_type $id            
     * @return $this
     */
    public function setId ($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     *         $taxonomies
     */
    public function getTaxonomies ()
    {
        return $this->taxonomies;
    }

    /**
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $taxonomies            
     * @return $this
     */
    public function setTaxonomies ($taxonomies)
    {
        $this->taxonomies = $taxonomies;
        return $this;
    }

    /**
     *
     * @return field_type
     *         $factory
     */
    public function getFactory ()
    {
        return $this->factory;
    }

    /**
     *
     * @return field_type
     *         $name
     */
    public function getName ()
    {
        return $this->name;
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

    public function __construct ()
    {
        $this->taxonomies = new ArrayCollection();
    }
}