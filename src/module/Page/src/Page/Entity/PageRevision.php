<?php
namespace Page\Entity;

use Doctrine\ORM\Mapping as ORM;
use Versioning\Entity\RevisionInterface;
use Versioning\Entity\RepositoryInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidEntity;

/**
 * A Page Revision.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_revision")
 */
class PageRevision extends UuidEntity implements RevisionInterface, PageRevisionInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="pageRevision", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @ORM\ManyToOne(targetEntity="PageRepository", inversedBy="revisions")
     */
    protected $page_repository;

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;

    /**
     * @ORM\Column(type="boolean", options={"default"})
     */
    protected $trashed;
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RevisionInterface::delete()
     */
    public function delete()
    {
    $this->page_repository->removeRevision($this);
    return $this;
     }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RevisionInterface::trash()
     */
    public function trash()
    {
        $this->trashed = TRUE;
        return $this;
    }
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RevisionInterface::getRepository()
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

    public function getRepository()
    {
        return $this->page_repository;
    }

    public function setRepository(RepositoryInterface $repository)
    {
        $this->page_repository = $repository;
        return $this;
    }

    public function getTimestamp()
    {
        return $this->date;
    }

    public function setContent($content){
        $this->content=$content;
        return $this;
    }
    public function getContent()
    {
        return $this->content;
    }
    
    public function setTitle($title){
        $this->title=$title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the date
     *
     * @param mixed $date            
     * @return self
     */
    public function setTimestamp(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Sets the author
     *
     * @param EntityInterface $user            
     * @return self
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
        return $this;
    }

    public function populate(array $data = array())
    {
        $this->injectFromArray('author', $data);
        $this->injectFromArray('title', $data);
        $this->injectFromArray('content', $data);
        $this->injectFromArray('date', $data);
        return $this;
    }

    private function injectFromArray($key, array $array, $default = NULL)
    {
        if (array_key_exists($key, $array)) {
            $this->$key = $array[$key];
        } elseif ($default !== NULL) {
            $this->$key = $default;
        }
    }
}
