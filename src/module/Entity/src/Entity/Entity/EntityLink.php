<?php
namespace Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * An
 * entity
 * link.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_link")
 */
class EntityLink
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="Entity",
     * inversedBy="parents")
     * @ORM\JoinColumn(name="child_id",
     * referencedColumnName="id")
     */
    public $child;

    /**
     * @ORM\ManyToOne(targetEntity="Entity",
     * inversedBy="children")
     * @ORM\JoinColumn(name="parent_id",
     * referencedColumnName="id")
     */
    public $parent;

    /**
     * @ORM\ManyToOne(targetEntity="EntityLinkType")
     * @ORM\JoinColumn(name="entity_link_type_id",
     * referencedColumnName="id")
     */
    public $type;

    /**
     * @ORM\Column(name="`order`", type="integer")
     */
    public $order;

    /**
     *
     * @return field_type
     *         $order
     */
    public function getOrder ()
    {
        return $this->order;
    }

    /**
     *
     * @param field_type $order            
     * @return $this
     */
    public function setOrder ($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     *
     * @return field_type
     *         $id
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     *
     * @return field_type
     *         $child
     */
    public function getChild ()
    {
        return $this->child;
    }

    /**
     *
     * @return field_type
     *         $parent
     */
    public function getParent ()
    {
        return $this->parent;
    }

    /**
     *
     * @return field_type
     *         $type
     */
    public function getType ()
    {
        return $this->type;
    }

    /**
     *
     * @param field_type $id            
     * @return $this
     */
    public function setId ($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param field_type $child            
     * @return $this
     */
    public function setChild ($child)
    {
        $this->child = $child;
        return $this;
    }

    /**
     *
     * @param field_type $parent            
     * @return $this
     */
    public function setParent ($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     *
     * @param field_type $type            
     * @return $this
     */
    public function setType ($type)
    {
        $this->type = $type;
        return $this;
    }
}
