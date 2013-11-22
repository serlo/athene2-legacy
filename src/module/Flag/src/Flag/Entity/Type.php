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
namespace Flag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="flag_type")
 */
class Type implements TypeInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Flag", mappedBy="type")
     */
    protected $flags;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;
    
    public function __construct()
    {
        $this->flags = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addFlag(FlagInterface $flag)
    {
        $this->flags->add($flag);
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}