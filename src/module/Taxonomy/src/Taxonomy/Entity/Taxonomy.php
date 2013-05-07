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
class Taxonomy extends AbstractEntity
{

    /**
     * @ORM\OneToMany(targetEntity="TaxonomyTerm", mappedBy="taxonomy")
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
     * @ORM\ManyToOne(targetEntity="Core\Entity\Language")
     */
    protected $language;

    public function __construct ()
    {
        $this->terms = new ArrayCollection();
    }
}