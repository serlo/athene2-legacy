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

/**
 * An entity type.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_type")
 */
class Type implements TypeInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\OneToMany(targetEntity="Entity", mappedBy="type")
     */
    protected $entities;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $name;

    public function __construct()
    {
        $this->entities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($className)
    {
        $this->name = $className;
        return $this;
    }
}