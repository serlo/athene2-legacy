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
namespace Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Link\Entity\LinkInterface;
use Link\Entity\LinkableInterface;
use Link\Entity\LinkTypeInterface;

/**
 * An
 * entity
 * link.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_link")
 */
class EntityLink implements LinkInterface
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

    public function __construct(LinkTypeInterface $type, $order)
    {
        $this->type = $type;
        $this->order = $order;
    }

    public function getPosition()
    {
        return $this->order;
    }

    public function setPosition($position)
    {
        $this->order = $position;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getChild()
    {
        return $this->child;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setChild(LinkableInterface $child)
    {
        $this->child = $child;
        return $this;
    }

    public function setParent(LinkableInterface $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function setType(LinkTypeInterface $type)
    {
        $this->type = $type;
        return $this;
    }
}
