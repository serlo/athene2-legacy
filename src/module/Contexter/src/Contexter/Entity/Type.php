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
namespace Contexter\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Comment ORM Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="context_type")
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
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Context", mappedBy="type")
     */
    protected $context;

	public function __construct()
    {
        $this->context = new ArrayCollection();
    }

    public function getId ()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function addContext(ContextInterface $context)
    {
        $this->context->add($context);
        return $this;
    }
}
