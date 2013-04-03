<?php
namespace Page\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_revision")
 */
class PageRevision extends AbstractEntity {
    
    /**
     * @ORM\ManyToOne(targetEntity="PageRepository", inversedBy="PageRevisions")
     **/
    protected $translation;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     **/
    protected $administrator;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     **/
    protected $author;

    /** @ORM\Column(type="text",length=255) */
    protected $title;

    /** @ORM\Column(type="text") */
    protected $content;

    /** @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"}) 
     */
    protected $date;
    
    /** @ORM\Column(type="datetime") */
    protected $administration_date;
    
	public function populate(array $data) {	
    	$this->title = $data['title'];
    	$this->content = $data['content'];
    	return $this;
	}
}