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
namespace Notification;

use Doctrine\Common\Collections\ArrayCollection;
use Event\Entity\EventLogInterface;
use User\Entity\UserInterface;

interface NotificationManagerInterface
{

    /**
     * @param UserInterface     $user
     * @param EventLogInterface $eventLog
     * @return self
     */
    public function createNotification(UserInterface $user, EventLogInterface $eventLog);

    /**
     * @param UserInterface $userService
     * @return ArrayCollection
     */
    public function findNotificationsBySubsriber(UserInterface $userService);
}