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

    public function __construct ()
    {
        $this->taxonomies = new ArrayCollection();
    }
}