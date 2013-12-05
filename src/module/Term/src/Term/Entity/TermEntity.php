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

use Language\Entity\Language;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Term.
 *
 * @ORM\Entity
 * @ORM\Table(name="term")
 */
class TermEntity implements TermEntityInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language")
     */
    protected $language;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="Taxonomy\Entity\TaxonomyTerm", mappedBy="term")
     */
    private $termTaxonomies;

    public function getId()
    {
        return $this->id;
    }

    public function getEntity()
    {
        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
}