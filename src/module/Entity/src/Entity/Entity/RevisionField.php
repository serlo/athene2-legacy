<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * An entity link.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_revision_field")
 */
class RevisionField
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Revision", inversedBy="fields")
     * @ORM\JoinColumn(name="entity_revision_id", referencedColumnName="id")
     */
    protected $revision;

    /**
     * @ORM\Column(type="string")
     */
    protected $field;

    /**
     * @ORM\Column(type="string")
     */
    protected $value;

    /**
     * @return field_type $entityRevisionId
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @return field_type $field
     */
    public function getName()
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $entityRevision
     */
    public function setRevision($entityRevision)
    {
        $this->revision = $entityRevision;
    }

    /**
     * @param field_type $field
     */
    public function setName($field)
    {
        $this->field = $field;
    }

    /**
     * @param field_type $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function __construct($revision, $field)
    {
        $this->revision = $revision;
        $this->field    = $field;
    }

    public function get($property)
    {
        return $this->$property;
    }

    public function set($property, $value)
    {
        return $this->$property = $value;
    }
}
