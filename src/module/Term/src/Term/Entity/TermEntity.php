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
namespace Term\Entity;

use Common\Filter\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Instance\Entity\InstanceAwareTrait;

/**
 * A Term.
 *
 * @ORM\Entity
 * @ORM\Table(name="term")
 */
class TermEntity implements TermEntityInterface
{
    use InstanceAwareTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Taxonomy\Entity\TaxonomyTerm", mappedBy="term")
     */
    protected $termTaxonomies;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSlug()
    {
        $filter = new Slugify();
        return $filter->filter($this->getName());
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}