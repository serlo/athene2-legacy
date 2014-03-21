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
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Event\Entity\EventLogInterface;
use Notification\Entity\NotificationInterface;
use User\Entity\UserInterface;

class NotificationManager implements NotificationManagerInterface
{

    use ClassResolverAwareTrait, \Common\Traits\ObjectManagerAwareTrait;

    public function createNotification(UserInterface $user, EventLogInterface $log)
    {
        $notification = $this->aggregateNotification($user, $log);

        $className = $this->getClassResolver()->resolveClassName('Notification\Entity\NotificationEventInterface');

        /* @var $notificationLog \Notification\Entity\NotificationEventInterface */
        $notificationLog = new $className();
        $notificationLog->setNotification($notification);
        $notification->setUser($user);
        $notification->setSeen(false);
        $notification->setTimestamp(new DateTime());
        $notificationLog->setEventLog($log);

        $this->getObjectManager()->persist($notification);
        $this->getObjectManager()->persist($notificationLog);

        return $this;
    }

    public function findNotificationsBySubscriber(UserInterface $user)
    {
        $className     = $this->getClassResolver()->resolveClassName('Notification\Entity\NotificationInterface');
        $criteria      = ['user' => $user->getId()];
        $order         = ['id' => 'desc'];
        $notifications = $this->getObjectManager()->getRepository($className)->findBy($criteria, $order);
        $collection    = new ArrayCollection;

        /* @var $notification NotificationInterface */
        foreach ($notifications as $notification) {
            if (!$notification->getEvents()->count()) {
                $this->objectManager->remove($notification);
                $this->objectManager->flush($notification);
                continue;
            }
            $collection->add($notification);
        }

        return $collection;
    }

    /**
     * @param UserInterface     $user
     * @param EventLogInterface $log
     * @return NotificationInterface
     */
    protected function aggregateNotification(UserInterface $user, EventLogInterface $log)
    {
        $className = $this->getClassResolver()->resolveClassName('Notification\Entity\NotificationInterface');
        return new $className();
    }
}