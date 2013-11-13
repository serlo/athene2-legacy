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
 *
 * @ORM\Entity
 * @ORM\Table(name="related_content_container")
 */
class Container extends UuidEntity implements ContainerInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="External",
     * mappedBy="container")
     */
    protected $externalRelations;

    /**
     * @ORM\OneToMany(targetEntity="Internal",
     * mappedBy="container")
     */
    protected $internalRelations;

    public function __construct()
    {
        $this->externalRelations = new ArrayCollection();
        $this->internalRelations = new ArrayCollection();
    }
    
    public function getExternalRelations()
    {
        return $this->externalRelations;
    }
    
    public function getInternalRelations()
    {
        return $this->internalRelations;
    }

    public function addExternalRelation(ExternalInterface $externalRelation)
    {
        $this->externalRelations->add($externalRelation);
        return $this;
    }

    public function addInternalRelation(InternalInterface $internalRelation)
    {
        $this->internalRelations->add($internalRelation);
        return $this;
    }
}