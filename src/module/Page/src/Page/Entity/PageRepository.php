<?php
namespace Page\Entity;

use Core\Entity\AbstractEntity;
use Core\Entity\Language;
use Doctrine\ORM\Mapping as ORM;
use Page\Entity\PageRevision;
use Versioning\Entity\RepositoryInterface;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_repository")
 */
class PageRepository extends AbstractEntity implements RepositoryInterface {
    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="translations")
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

    public function __construct() {
        $this->revisions = new \Doctrine\Common\Collections\ArrayCollection();
    }

	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RepositoryInterface::getRevisions()
	 */
	public function getRevisions() {
		return $this->revisions;
	}

}