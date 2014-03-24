<?php
/**
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license   LGPL
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Notification\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use User\Entity\UserInterface;


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
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
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

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getTimestamp()
    {
        return $this->date;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getSeen()
    {
        return $this->seen;
    }

    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

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
        return $this->getEvents()->current()->getEvent()->getName();
    }

    public function addEvent(NotificationEventInterface $event)
    {
        $this->events->add($event);

        return $this;
    }

    public function getActors()
    {
        $collection = new ArrayCollection();
        foreach ($this->getEvents() as $event) {
            /* @var $event NotificationEvent */
            $collection->add($event->getActor());
        }

        return $collection;
    }

    public function getObjects()
    {
        $collection = new ArrayCollection();
        foreach ($this->getEvents() as $event) {
            /* @var $event NotificationEvent */
            $collection->add($event->getObject());
        }

        return $collection;
    }

    public function getParameters()
    {
        $collection = new ArrayCollection();
        foreach ($this->getEvents() as $event) {
            /* @var $event NotificationEvent */
            $collection->add($event->getParameters());
        }

        return $collection;
    }

    public function setTimestamp(DateTime $timestamp)
    {
        $this->date = $timestamp;
    }
}