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
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Notification\Entity\NotificationLogInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification_event_log")
 */
class NotificationLog implements NotificationLogInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="actor_id", referencedColumnName="id")
     */
    protected $actor;

    /**
     * @ORM\ManyToOne(targetEntity="Event\Entity\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event;

    /**
     * @ORM\ManyToOne(targetEntity="Uuid\Entity\Uuid")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     */
    protected $object;

    /**
     * @ORM\ManyToOne(targetEntity="Uuid\Entity\Uuid")
     * @ORM\JoinColumn(name="reference_id", referencedColumnName="id", nullable=true)
     */
    protected $reference;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    public function getId()
    {
        return $this->id;
    }

    public function getActor()
    {
        return $this->actor;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setObject(\Uuid\Entity\UuidInterface $uuid)
    {
        $this->object = $uuid;
        return $this;
    }

    public function setReference(\Uuid\Entity\UuidInterface $uuid = NULL)
    {
        $this->reference = $uuid;
        return $this;
    }

    public function setEvent(\Event\Entity\EventInterface $event)
    {
        $this->event = $event;
        return $this;
    }

    public function setActor(\User\Entity\UserInterface $actor)
    {
        $this->actor = $actor;
        return $this;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }
}