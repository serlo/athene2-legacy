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
use Entity\Entity\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="term_taxonomy_entity")
 */
class TaxonomyTermEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     * targetEntity="TaxonomyTerm",
     * inversedBy="termTaxonomyEntity",
     * cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="term_taxonomy_id", referencedColumnName="id", nullable=false)
     */
    protected $termTaxonomy;

    /**
     * @ORM\ManyToOne(
     * targetEntity="Entity\Entity\Entity",
     * inversedBy="termTaxonomyEntity",
     * cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="id", nullable=false)
     */
    protected $entity;

    /**
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     *
     * @return field_type $termTaxonomy
     */
    public function getTaxonomyTerm()
    {
        return $this->termTaxonomy;
    }

    /**
     *
     * @return field_type $entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     *
     * @return field_type $position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     *
     * @param field_type $termTaxonomy            
     * @return $this
     */
    public function setTaxonomyTerm($termTaxonomy)
    {
        $this->termTaxonomy = $termTaxonomy;
        return $this;
    }

    /**
     *
     * @param field_type $entity            
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     *
     * @param field_type $position            
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
    
    public function __construct(TaxonomyTermModelInterface $termTaxonomy, EntityInterface $entity)
    {
        $this->setTaxonomyTerm($termTaxonomy);
        $this->setEntity($entity);
        $this->position = 0;
    }
}