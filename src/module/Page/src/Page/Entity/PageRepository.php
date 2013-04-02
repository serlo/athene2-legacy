<?php
namespace Page\Entity;

use Core\Entity\AbstractEntity;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_translation")
 */
class PageRepository extends AbstractEntity {
    /**
     * @ManyToOne(targetEntity="Page", inversedBy="PageTranslations")
     **/
    protected $page;
    
    /**
     * @ManyToOne(targetEntity="Language")
     **/
    protected $language;
    
    /**
     * @OneToMany(targetEntity="PageRevision", mappedBy="PageRepository")
     **/
    protected $revisions;
    
    /**
     * @OneToOne(targetEntity="PageRevision")
     **/
    protected $currentRevision;

    /** @Column(type="text",length=255) */
    protected $uri;

    /** @Column(type="text",length=255) */
    protected $name;

    public function __construct() {
        $this->revisions = new \Doctrine\Common\Collections\ArrayCollection();
    }
}