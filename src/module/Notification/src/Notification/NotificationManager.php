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

use ClassResolver\ClassResolverAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Event\Entity\EventLogInterface;
use Notification\Entity\NotificationLogInterface;

class NotificationManager implements NotificationManagerInterface
{

    use ClassResolverAwareTrait, \Common\Traits\ObjectManagerAwareTrait;

    public function createNotification(\User\Entity\UserInterface $user, EventLogInterface $log)
    {
        $notification = $this->aggregateNotification($user, $log);

        $className = $this->getClassResolver()->resolveClassName('Notification\Entity\NotificationEventInterface');
        /* @var $notificationLog \Notification\Entity\NotificationEventInterface */
        $notificationLog = new $className();

        // $notification->addEvent($notificationLog);
        $notificationLog->setNotification($notification);
        $notification->setUser($user);
        $notification->setSeen(false);
        $notification->setTimestamp(new \DateTime('NOW'));
        $notificationLog->setEventLog($log);

        $this->getObjectManager()->persist($notification);
        $this->getObjectManager()->persist($notificationLog);

        return $this;
    }

    public function findNotificationsBySubsriber(\User\Entity\UserInterface $userService)
    {
        $notifications = $this->getObjectManager()->getRepository(
                $this->getClassResolver()->resolveClassName('Notification\Entity\NotificationInterface')
            )->findBy(
                [
                    'user' => $userService->getId()
                ],
                ['id' => 'desc']
            );

        return new ArrayCollection($notifications);
    }

    /**
     * @param \Entity\UserInterface    $user
     * @param NotificationLogInterface $log
     * @return \Notification\Entity\NotificationInterface
     */
    protected function aggregateNotification(\User\Entity\UserInterface $user, EventLogInterface $log)
    {
        $className = $this->getClassResolver()->resolveClassName('Notification\Entity\NotificationInterface');

        return new $className();
    }
}