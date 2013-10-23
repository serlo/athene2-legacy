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
namespace Uuid\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="uuid")
 */
class Uuid implements UuidInterface
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    protected $uuid;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $trashed;

    /**
     * @ORM\OneToOne(targetEntity="Entity\Entity\Entity", mappedBy="id")
     */
    protected $entity;

    /**
     * @ORM\OneToOne(targetEntity="Taxonomy\Entity\TermTaxonomy", mappedBy="id")
     */
    protected $termTaxonomy;

    /**
     * @ORM\OneToOne(targetEntity="Discussion\Entity\Comment", mappedBy="id")
     */
    protected $comment;

    /**
     * @ORM\OneToOne(targetEntity="User\Entity\User", mappedBy="id")
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="Entity\Entity\Revision", mappedBy="id")
     */
    protected $entityRevision;

    /**
     * @ORM\OneToOne(targetEntity="Page\Entity\PageRepository", mappedBy="id")
     */
    protected $pageRepository;

    /**
     *
     * @return field_type $trashed
     */
    public function getTrashed()
    {
        return $this->trashed;
    }

    /**
     *
     * @param bool $trashed            
     * @return $this
     */
    public function setTrashed($trashed)
    {
        $this->trashed = (bool) $trashed;
        return $this;
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
     * @return field_type $termTaxonomy
     */
    public function getTermTaxonomy()
    {
        return $this->termTaxonomy;
    }

    /**
     *
     * @return field_type $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @return field_type $entityRevision
     */
    public function getEntityRevision()
    {
        return $this->entityRevision;
    }

    public function is($type)
    {
        $type = 'get' . ucfirst($type);
        if (method_exists($this, $type)) {
            echo $type . ":" . is_object($this->$type());
            return is_object($this->$type());
        }
        return false;
    }

    function __construct()
    {
        $this->uuid = hash('crc32b', uniqid('uuid.', true));
        $this->trashed = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function hydrate(UuidHolder $entity)
    {
        $entity->setUuid($this);
        return $this;
    }
}