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
namespace Subject\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Subject.
 *
 * @ORM\Entity
 * @ORM\Table(name="subject")
 */
class Subject extends AbstractEntity implements SubjectEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="SubjectFactory", inversedBy="subjects")
     * @ORM\JoinColumn(name="subject_factory_id", referencedColumnName="id")
     */
    protected $factory;

    /**
     * @ORM\OneToMany(targetEntity="Taxonomy\Entity\Taxonomy", mappedBy="subject")
     */
    protected $taxonomies;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $name;

    public function __construct()
    {
        $this->taxonomies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     *
     * @return field_type $factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     *
     * @return field_type $taxonomies
     */
    public function getTaxonomies()
    {
        return $this->taxonomies;
    }

    /**
     *
     * @return field_type $name
     */
    public function getName()
    {
        return $this->name;
    }
}