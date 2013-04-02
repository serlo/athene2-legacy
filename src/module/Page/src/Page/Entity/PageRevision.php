<?php
namespace Page\Entity;

use Core\Entity\AbstractEntity;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_revision")
 */
class PageRevision extends AbstractEntity {
    
    /**
     * @ManyToOne(targetEntity="PageRepository", inversedBy="PageRevisions")
     **/
    protected $translation;

    /**
     * @ManyToOne(targetEntity="User")
     **/
    protected $confirmer;
    
    /**
     * @ManyToOne(targetEntity="User")
     **/
    protected $author;

    /** @Column(type="text",length=255) */
    protected $title;

    /** @Column(type="text") */
    protected $content;

    /** @Column(type="datetime") */
    protected $date;
    
    /** @Column(type="datetime") */
    protected $confirmation_date;
    
}