<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="user_log")
 */
class UserLog
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="logs")
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;

    /**
     * @ORM\Column(type="string") *
     */
    protected $action;

    /**
     * @ORM\Column(type="integer") *
     */
    protected $ref_id;

    /**
     * @ORM\Column(type="string") *
     */
    protected $ref;

    /**
     * @ORM\Column(type="string") *
     */
    protected $note;
    
    /**
     * @ORM\Column(type="string") *
     */
    protected $event;
    
    /**
     * @ORM\Column(type="string") *
     */
    protected $source;
	/**
     * @return field_type $id
     */
    public function getId ()
    {
        return $this->id;
    }

	/**
     * @return field_type $user
     */
    public function getUser ()
    {
        return $this->user;
    }

	/**
     * @return field_type $date
     */
    public function getDate ()
    {
        return $this->date;
    }

	/**
     * @return field_type $action
     */
    public function getAction ()
    {
        return $this->action;
    }

	/**
     * @return field_type $ref_id
     */
    public function getRef_id ()
    {
        return $this->ref_id;
    }

	/**
     * @return field_type $ref
     */
    public function getRef ()
    {
        return $this->ref;
    }

	/**
     * @return field_type $note
     */
    public function getNote ()
    {
        return $this->note;
    }

	/**
     * @return field_type $event
     */
    public function getEvent ()
    {
        return $this->event;
    }

	/**
     * @return field_type $source
     */
    public function getSource ()
    {
        return $this->source;
    }

	/**
     * @param field_type $id
     * @return $this
     */
    public function setId ($id)
    {
        $this->id = $id;
        return $this;
    }

	/**
     * @param field_type $user
     * @return $this
     */
    public function setUser ($user)
    {
        $this->user = $user;
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

	/**
     * @param field_type $action
     * @return $this
     */
    public function setAction ($action)
    {
        $this->action = $action;
        return $this;
    }

	/**
     * @param field_type $ref_id
     * @return $this
     */
    public function setRef_id ($ref_id)
    {
        $this->ref_id = $ref_id;
        return $this;
    }

	/**
     * @param field_type $ref
     * @return $this
     */
    public function setRef ($ref)
    {
        $this->ref = $ref;
        return $this;
    }

	/**
     * @param field_type $note
     * @return $this
     */
    public function setNote ($note)
    {
        $this->note = $note;
        return $this;
    }

	/**
     * @param field_type $event
     * @return $this
     */
    public function setEvent ($event)
    {
        $this->event = $event;
        return $this;
    }

	/**
     * @param field_type $source
     * @return $this
     */
    public function setSource ($source)
    {
        $this->source = $source;
        return $this;
    }

}