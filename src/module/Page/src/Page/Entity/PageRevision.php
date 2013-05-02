<?php
namespace Page\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Versioning\Entity\RevisionInterface;


/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_revision")
 */
class PageRevision extends AbstractEntity implements RevisionInterface {
    
    /**
     * @ORM\ManyToOne(targetEntity="PageRepository", inversedBy="revisions")
     * @ORM\JoinColumn(name="page_repository_id", referencedColumnName="id")
     **/
    protected $repository;
    
    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     **/
    protected $author;

    /** @ORM\Column(type="text",length=255) */
    protected $title;

    /** @ORM\Column(type="text") */
    protected $content;

    /** @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"}) 
     */
    protected $date;
    
	public function populate(array $data) {	
    	$this->title = $data['title'];
    	$this->content = $data['content'];
    	return $this;
	}
	
	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::delete()
	 */
	public function delete() {
		// TODO Auto-generated method stub
	}

	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::trash()
	 */
	public function trash() {
		// TODO Auto-generated method stub
	}
	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::getRepository()
	 */
	public function getRepository() {
		return $this->repository;
	}

	
}
