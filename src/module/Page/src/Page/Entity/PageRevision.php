<?php
namespace Page\Entity;

use Core\Entity\EntityInterface;
use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Versioning\Entity\RevisionInterface;
use Versioning\Entity\RepositoryInterface;


/**
 * A Page Revision.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_revision")
 */
class PageRevision extends AbstractEntity implements RevisionInterface {
    
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", unique=true)
	 * @ORM\GeneratedValue(strategy="AUTO") *
	 */
	protected $id;
	
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\ManyToOne(targetEntity="User\Entity\User") *
	 */
	protected $author_id;
	
    /**
     * @ORM\Column(type="integer") 
     * @ORM\ManyToOne(targetEntity="Page")
     **/
    protected $page_repository;
    
   

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

	public function setRepository(RepositoryInterface $repository) {
		$this->repository = $repository;
		
		return $this;
		
	}
	
	public function getDate() {
	
	}
	
	public function getContent() {
	return $this->content;
	}
	
	public function getTitle() {
	return $this->title;
	}
	
	
	public function getAuthor() {
	
	}
	
	/**
	 * Sets the date
	 *
	 * @param mixed $date
	 * @return $this
	 */
	public function setDate($date){
		
	}
	
	/**
	 * Sets the author
	 *
	 * @param EntityInterface $user
	 * @return $this
	*/
	public function setAuthor($user){
		
	}
	
}
