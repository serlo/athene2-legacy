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
 * @ORM\Table(name="taxonomy_factory")
 */
class TaxonomyFactory extends AbstractEntity
{
    /**
     * @ORM\OneToMany(targetEntity="Taxonomy", mappedBy="factory")
     */
    protected $taxonomies;

    /**
     * @ORM\Column(type="text",name="class_name",length=255)
     */
    protected $className;

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection $taxonomies
     */
    public function getTaxonomies ()
    {
        return $this->taxonomies;
    }

	/**
     * @return field_type $className
     */
    public function getName ()
    {
        return $this->className;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $taxonomies
     * @return $this
     */
    public function setTaxonomies ($taxonomies)
    {
        $this->taxonomies = $taxonomies;
        return $this;
    }

	/**
     * @param field_type $className
     * @return $this
     */
    public function setName ($className)
    {
        $this->className = $className;
        return $this;
    }

	public function __construct ()
    {
        $this->taxonomies = new ArrayCollection();
    }
}