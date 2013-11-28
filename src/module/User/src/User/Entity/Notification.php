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
use User\Notification\Entity\NotificationInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification")
 */
class Notification implements NotificationInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="NotificationEvent",
     * mappedBy="notification")
     */
    protected $events;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $seen;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    public function getTimestamp()
    {
        return $this->date;
    }

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     *
     * @return field_type $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return field_type $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @return field_type $seen
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     *
     * @param field_type $user            
     * @return $this
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @param field_type $seen            
     * @return $this
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Entity\NotificationInterface::getEvents()
     */
    public function getEvents()
    {
        $events = new ArrayCollection();
        foreach ($this->events as $event) {
            $events->add($event->getEventLog());
        }
        return $events;
    }

    public function getEventName()
    {
        return $this->getEvents()
            ->current()
            ->getEvent()
            ->getName();
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Entity\NotificationInterface::addEvent()
     */
    public function addEvent(\User\Notification\Entity\NotificationEventInterface $event)
    {
        $this->events->add($event);
        return $this;
    }
    /*
     * (non-PHPdoc) @see \User\Notification\Entity\NotificationInterface::getActors()
     */
    public function getActors()
    {
        $collection = new ArrayCollection();
        foreach ($this->getEvents() as $event) {
            /* @var $event NotificationEvent */
            $collection->add($event->getActor());
        }
        return $collection;
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Entity\NotificationInterface::getObjects()
     */
    public function getObjects()
    {
        $collection = new ArrayCollection();
        foreach ($this->getEvents() as $event) {
            /* @var $event NotificationEvent */
            $collection->add($event->getObject());
        }
        return $collection;
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Entity\NotificationInterface::getParameters()
     */
    public function getParameters()
    {
        $collection = new ArrayCollection();
        foreach ($this->getEvents() as $event) {
            /* @var $event NotificationEvent */
            $collection->add($event->getParameters());
        }
        return $collection;
    }

    public function setTimestamp(\DateTime $timestamp)
    {
        $this->date = $timestamp;
        return $this;
    }
}