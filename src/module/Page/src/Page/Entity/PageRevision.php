<?php
namespace Page\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\UserInterface;
use Uuid\Entity\Uuid;
use Versioning\Entity\RepositoryInterface;

/**
 * A Page Revision.
 *
 * @ORM\Entity
 * @ORM\Table(name="page_revision")
 */
class PageRevision extends Uuid implements PageRevisionInterface
{

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


    public function getRepository()
    {
        return $this->page_repository;
    }

    public function setRepository(RepositoryInterface $repository)
    {
        $this->page_repository = $repository;
        return $this;
    }

    public function getDate()
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

    public function populate(array $data = [])
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
    public function set($key, $value)
    {
            $this->$key = $value;
    }
	

}
