<?php
namespace Page\Entity;

use Core\Entity\AbstractEntity;
use Core\Entity\Language;
use Doctrine\ORM\Mapping as ORM;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_translation")
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
     * @ORM\OneToMany(targetEntity="PageRevision", mappedBy="PageRepository")
     **/
    protected $revisions;
    
    /**
     * @ORM\OneToOne(targetEntity="PageRevision")
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