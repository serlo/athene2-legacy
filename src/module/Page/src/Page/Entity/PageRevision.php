<?php
namespace Page\Entity;

use Doctrine\ORM\Mapping as ORM;
use Versioning\Entity\RevisionInterface;
use Versioning\Entity\RepositoryInterface;
use User\Entity\UserInterface;

/**
 * A Page Revision.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_revision")
 */
class PageRevision implements RevisionInterface {

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

	/**
	 * @ORM\Column(type="boolean", options={"default"})
	 */
	protected $trashed;
	
	public function populate(array $data) {
		$this->title = $data['title'];
		$this->content = $data['content'];
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::delete()
	*/
	public function delete() {
		
	        return $this;
		        
	}

	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::trash()
	*/
	public function trash() {
		
        $this->trashed = TRUE;
        return $this;	}
	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::getRepository()
	*/

        public function untrash()
        {
        	$this->trashed = FALSE;
        	return $this;
        }
        
        public function isTrashed()
        {
        	return $this->trashed;
        }
        
    
        
	public function getRepository() {
		return $this->repository;
	}

	public function setRepository(RepositoryInterface $repository) {
		$this->repository = $repository;

		return $this;

	}

	public function getDate() {
		return $this->date;
	}

	public function getContent() {
		return $this->content;
	}

	public function getTitle() {
		return $this->title;
	}


	public function getAuthor() {
		return $this->author_id;
	}

	/**
	 * Sets the date
	 *
	 * @param mixed $date
	 * @return $this
	 */
	public function setDate(\DateTime $date)
	{
		$this->date = $date;
		return $this;
	}


	/**
	 * Sets the author
	 *
	 * @param EntityInterface $user
	 * @return $this
	 */

	public function setAuthor(UserInterface $author)
	{
		$this->author = $author;
		return $this;
	}

	public function getId ()
	{
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}



}
