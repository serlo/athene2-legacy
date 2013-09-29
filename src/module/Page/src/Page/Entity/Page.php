<?php
namespace Page\Entity;

use Doctrine\ORM\Mapping as ORM;
use Uuid\Entity\UuidEntity;
use Versioning\Entity\RepositoryInterface;
use Versioning\Entity\RevisionInterface;

/**
 * A blog post.
 *
 * @ORM\Entity
 * @ORM\Table(name="page")
 *
 */
class Page extends UuidEntity implements RepositoryInterface
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\ManyToOne(targetEntity="User\Entity\Role") *
	 */
	protected $role_id;

	/**
	 * @ORM\ManyToOne(targetEntity="Language\Entity\Language") *
	 */
	protected $language;


	/**
	 * @ORM\Column(type="string") *
	 */
	protected $slug;

	/**
	 * @ORM\OneToMany(targetEntity="Page\Entity\PageRevision", mappedBy="id")  *
	 */
	protected $current_revision_id;


	/**
	 * @ORM\OneToMany(targetEntity="PageRevision", mappedBy="repository")
	 */
	protected $revisions;


	public function __get ($property)
	{
		return $this->$property;
	}

	/**
	 * Magic setter to save protected properties.
	 *
	 * @param string $property
	 * @param mixed $value
	 *
	 */
	public function __set ($property, $value)
	{
		$this->$property = $value;
	}


	public function getRevisions() {

		return $this->revisions;

	}

	public function newRevision() {
		$revision = new PageRevision();
		$revision->setRepository($this);
		return $revision;
	}

	public function getCurrentRevision() {
	return $this->getCurrentRevision();
	
	}

	public function hasCurrentRevision() {
		return $this->getCurrentRevision() !== NULL;
	}
	
  public function setCurrentRevision (RevisionInterface $currentRevision)
  
    {
        $this->currentRevision = $currentRevision;
        return $this;
    }

}
