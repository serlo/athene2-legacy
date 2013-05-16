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

use Core\Entity\AbstractEntity;
use Core\Entity\Language;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Term.
 *
 * @ORM\Entity
 * @ORM\Table(name="term")
 */
class Term extends AbstractEntity implements TermEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Core\Entity\Language")
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
     * @ORM\OneToMany(targetEntity="Taxonomy\Entity\TermTaxonomy", mappedBy="term")
     */
    private $termTaxonomies;

    /**
     *
     * @return field_type $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     *
     * @return field_type $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return field_type $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     *
     * @param field_type $language            
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     *
     * @param field_type $name            
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param field_type $slug            
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
}