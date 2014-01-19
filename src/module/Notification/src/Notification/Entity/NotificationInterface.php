<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Notification\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Event\Entity\EventLogInterface;
use User\Entity;

interface NotificationInterface
{

    /**
     * @param bool $seen
     * @return self
     */
    public function setSeen($seen);

    /**
     * @return bool
     */
    public function getSeen();

    /**
     * @return string
     */
    public function getEventName();

    /**
     * @return Entity\User
     */
    public function getUser();

    /**
     * @param Entity\User $user
     * @return self
     */
    public function setUser(Entity\UserInterface $user);

    /**
     * @return EventLogInterface[]
     */
    public function getEvents();

    /**
     * @param NotificationEventInterface $event
     * @return self
     */
    public function addEvent(NotificationEventInterface $event);

    /**
     * @return ArrayCollection
     */
    public function getActors();

    /**
     * @return ArrayCollection
     */
    public function getObjects();

    /**
     * @return ArrayCollection
     */
    public function getParameters();

    /**
     * @return \DateTime $timestamp
     */
    public function getTimestamp();

    /**
     * @return \DateTime $timestamp
     */
    public function setTimestamp(\DateTime $timestamp);
}