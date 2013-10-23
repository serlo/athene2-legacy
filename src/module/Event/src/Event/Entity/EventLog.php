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
namespace Event\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\UserInterface;
use Uuid\Entity\UuidHolder;
use Uuid\Entity\UuidInterface;
use Language\Entity\LanguageInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_log")
 */
class EventLog implements EventLogInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="actor_id", referencedColumnName="id")
     */
    protected $actor;

    /**
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event;

    /**
     * @ORM\ManyToOne(targetEntity="Uuid\Entity\Uuid")
     * @ORM\JoinColumn(name="uuid_id", referencedColumnName="id")
     */
    protected $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage(LanguageInterface $language)
    {
        $this->language = $language;
        return $this;
    }

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

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setActor(UserInterface $actor)
    {
        $this->actor = $actor;
        return $this;
    }

    public function setEvent(EventInterface $event)
    {
        $this->event = $event;
        return $this;
    }

    public function setUuid(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
}