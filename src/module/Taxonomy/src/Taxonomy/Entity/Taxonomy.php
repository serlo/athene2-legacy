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
namespace Taxonomy\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="taxonomy")
 */
class Taxonomy implements TaxonomyInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="TaxonomyTerm", mappedBy="taxonomy")
     * @ORM\OrderBy({"weight" = "ASC"})
     */
    protected $terms;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\LanguageEntity")
     */
    protected $language;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyType", inversedBy="taxonomies")
     * @ORM\JoinColumn(name="taxonomy_type_id", referencedColumnName="id")
     */
    protected $type;

    public function __construct()
    {
        $this->terms = new ArrayCollection();
    }

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
     * @return field_type $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param field_type $id            
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

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

    public function addTerm($term)
    {
        $this->getTerms()->add($term);
    }

    public function getName()
    {
        return is_object($this->getType()) ? $this->getType()->getName() : '';
    }

    public function getSaplings()
    {
        $collection = new ArrayCollection();
        $terms = $this->getTerms();
        foreach ($terms as $entity) {
            if (! $entity->hasParent() || ($entity->hasParent() && $entity->getParent()->getTaxonomy() !== $this)) {
                $collection->add($entity);
            }
        }
        return $collection;
    }
}