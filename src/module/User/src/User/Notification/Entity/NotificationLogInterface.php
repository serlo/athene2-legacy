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
namespace User\Notification\Entity;

use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;
use Language\Entity\LanguageInterface;
use Event\Entity\EventInterface;

interface NotificationLogInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @param UuidInterface $uuid            
     * @return $this
     */
    public function setObject(UuidInterface $uuid);

    /**
     *
     * @param UuidInterface $uuid            
     * @return $this
     */
    public function setReference(UuidInterface $uuid = NULL);

    /**
     *
     * @param EventInterface $event            
     * @return $this
     */
    public function setEvent(EventInterface $event);

    /**
     *
     * @param UserInterface $actor            
     * @return $this
     */
    public function setActor(UserInterface $actor);

    /**
     *
     * @return UuidInterface
     */
    public function getObject();

    /**
     *
     * @return UuidInterface NULL
     */
    public function getReference();

    /**
     *
     * @return UserInterface
     */
    public function getActor();

    /**
     *
     * @return EventInterface
     */
    public function getEvent();

    /**
     *
     * @return \DateTime
     */
    public function getDate();

    /**
     *
     * @param \DateTime $date            
     * @return $this
     */
    public function setDate(\DateTime $date);
}