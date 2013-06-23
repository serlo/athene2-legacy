<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Issue\Entity;

use Doctrine\ORM\Mapping as ORM;
use Uuid\Entity\UuidEntity;
use User\Entity\User;

/**
 * An issue
 *
 * @ORM\Entity
 * @ORM\Table(name="issue")
 */
class IssueEntity extends UuidEntity implements IssueInterface
{

    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $title;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $closed;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User", inversedBy="issues")
     */
    protected $author;
    
    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Uuid\Entity\Uuid")
     */
    protected $on;
    
	/**
     * @return field_type $on
     */
    public function getOn ()
    {
        return $this->on;
    }

	/**
     * @param field_type $on
     * @return $this
     */
    public function setOn ($on)
    {
        $this->on = $on;
        return $this;
    }

	/**
     * @return field_type $title
     */
    public function getTitle ()
    {
        return $this->title;
    }

	/**
     * @return field_type $content
     */
    public function getContent ()
    {
        return $this->content;
    }

	/**
     * @return field_type $author
     */
    public function getAuthor ()
    {
        return $this->author;
    }

	/**
     * @return field_type $date
     */
    public function getDate ()
    {
        return $this->date;
    }

	/**
     * @param field_type $title
     * @return $this
     */
    public function setTitle ($title)
    {
        $this->title = $title;
        return $this;
    }

	/**
     * @param field_type $content
     * @return $this
     */
    public function setContent ($content)
    {
        $this->content = $content;
        return $this;
    }

	/**
     * @param field_type $author
     * @return $this
     */
    public function setAuthor (User $author)
    {
        $this->author = $author;
        return $this;
    }

	/**
     * @param field_type $date
     * @return $this
     */
    public function setDate ($date)
    {
        $this->date = $date;
        return $this;
    }
    
    public function isClosed(){
        return $this->closed == true;
    }
    
    public function isOpen(){
        return $this->isClosed();
    }
    
    public function __construct($uuid = NULL){
        $this->title = '';
        $this->content = '';
        $this->closed = false;
        return parent::__construct($uuid);
    }
}