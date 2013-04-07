<?php
namespace Page\Entity;

use Core\Entity\AbstractEntity;
use Core\Entity\Language;
use Doctrine\ORM\Mapping as ORM;
use Page\Entity\PageRevision;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_repository")
 */
class PageRepository extends AbstractEntity {
    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="PageTranslations")
     **/
    protected $page;
    
    /**
     * @ORM\ManyToOne(targetEntity="Core\Entity\Language")
     **/
    protected $language;
    
    /**
     * @ORM\OneToMany(targetEntity="Page\Entity\PageRevision", mappedBy="repository")
     **/
    protected $revisions;
    
    /**
     * @ORM\OneToOne(targetEntity="PageRevision")
     * @ORM\JoinColumn(name="current_revision_id", referencedColumnName="id")
     **/
    protected $currentRevision;

    /** @ORM\Column(type="text",length=255) */
    protected $slug;

    /** @ORM\Column(type="text",length=255) */
    protected $name;

    public function __construct() {
        $this->revisions = new \Doctrine\Common\Collections\ArrayCollection();
    }
}