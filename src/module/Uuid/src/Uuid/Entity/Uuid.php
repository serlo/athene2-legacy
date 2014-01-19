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
namespace Uuid\Entity;

use Doctrine\ORM\Mapping as ORM;
use Uuid\Exception;

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
     * @ORM\OneToOne(targetEntity="Entity\Entity\Entity", mappedBy="id", fetch="EXTRA_LAZY")
     */
    protected $entity;

    /**
     * @ORM\OneToOne(targetEntity="Taxonomy\Entity\TaxonomyTerm", mappedBy="id", fetch="EXTRA_LAZY")
     */
    protected $taxonomyTerm;

    /**
     * @ORM\OneToOne(targetEntity="Upload\Entity\Upload", mappedBy="id", fetch="EXTRA_LAZY")
     */
    protected $upload;

    /**
     * @ORM\OneToOne(targetEntity="Discussion\Entity\Comment", mappedBy="id", fetch="EXTRA_LAZY")
     */
    protected $comment;

    /**
     * @ORM\OneToOne(targetEntity="User\Entity\User", mappedBy="id", fetch="EXTRA_LAZY")
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="Blog\Entity\Post", mappedBy="id", fetch="EXTRA_LAZY")
     */
    protected $blogPost;

    /**
     * @ORM\OneToOne(targetEntity="Entity\Entity\Revision", mappedBy="id", fetch="EXTRA_LAZY")
     */
    protected $entityRevision;

    /**
     * @ORM\OneToOne(targetEntity="Page\Entity\PageRepository", mappedBy="id", fetch="EXTRA_LAZY")
     */
    protected $pageRepository;

    /**
     * @ORM\OneToOne(targetEntity="Page\Entity\PageRevision", mappedBy="id", fetch="EXTRA_LAZY")
     */
    protected $pageRevision;

    /**
     * @return field_type $trashed
     */
    public function getTrashed()
    {
        return $this->trashed;
    }

    /**
     * @param bool $trashed
     * @return self
     */
    public function setTrashed($trashed)
    {
        $this->trashed = (bool)$trashed;

        return $this;
    }

    public function getHolderName()
    {
        foreach (get_object_vars($this) as $key => $value) {
            if ($this->is($key)) {
                return $key;
            }
        }

        throw new Exception\RuntimeException('Could not determine which holder this uuid belongs to.');
    }

    public function getHolder()
    {
        foreach (get_object_vars($this) as $key => $value) {
            if ($this->is($key)) {
                return $value;
            }
        }

        throw new Exception\RuntimeException('Could not determine which holder this uuid belongs to.');
    }

    function __construct()
    {
        $this->uuid    = hash('crc32b', uniqid('uuid.', true));
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

    public function is($type)
    {
        if (property_exists($this, $type)) {
            return is_object($this->$type);
        }

        return false;
    }

    public function setHolder($key, $object)
    {
        if (property_exists($this, $key)) {
            $this->$key = $object;
        }

        return $this;
    }
}
