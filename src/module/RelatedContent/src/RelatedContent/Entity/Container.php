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
namespace RelatedContent\Entity;

use Uuid\Entity\UuidEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="related_content_container")
 */
class Container extends UuidEntity implements ContainerInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $uuid;

    /**
     * @ORM\OneToMany(targetEntity="Holder",
     * mappedBy="container")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $holders;

    public function getHolders()
    {
        return $this->holders;
    }

    public function __construct()
    {
        $this->holders = new ArrayCollection();
        $this->internalRelations = new ArrayCollection();
    }

    public function addHolder(HolderInterface $holder)
    {
        $this->holders->add($holder);
        return $this;
    }
}