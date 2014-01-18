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

use Event\Entity\EventLogInterface;

interface NotificationEventInterface
{

    /**
     * @return EventLogInterface
     */
    public function getEventLog();

    /**
     * @param EventLogInterface $eventLog
     * @return self;
     */
    public function setEventLog(EventLogInterface $eventLog);

    /**
     * @return int
     */
    public function getId();

    /**
     * @return NotificationInterface
     */
    public function getNotification();

    /**
     * @param NotificationInterface $notification
     * @return self
     */
    public function setNotification(NotificationInterface $notification);
}