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
use Common\Traits\FlushableTrait;
use Common\Traits\ObjectManagerAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Event\Entity\EventLogInterface;
use Notification\Entity\NotificationInterface;
use User\Entity\UserInterface;
use Zend\EventManager\EventManagerAwareTrait;

class NotificationManager implements NotificationManagerInterface
{
    use ClassResolverAwareTrait, ObjectManagerAwareTrait;
    use FlushableTrait;

    public function createNotification(UserInterface $user, EventLogInterface $log)
    {
        /* @var $notificationLog \Notification\Entity\NotificationEventInterface */
        $notification    = $this->aggregateNotification($user, $log);
        $class           = 'Notification\Entity\NotificationEventInterface';
        $className       = $this->getClassResolver()->resolveClassName($class);
        $notificationLog = new $className();

        $notification->setUser($user);
        $notification->setSeen(false);
        $notification->setTimestamp(new DateTime());
        $notification->addEvent($notificationLog);
        $notificationLog->setEventLog($log);
        $notificationLog->setNotification($notification);

        $this->getObjectManager()->persist($notification);
        $this->getObjectManager()->persist($notificationLog);

        return $notification;
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
            if ($notification->getEvents()->count() < 1) {
                $this->objectManager->remove($notification);
                $this->objectManager->flush($notification);
                continue;
            }
            $collection->add($notification);
        }

        return $collection;
    }

    public function markRead(UserInterface $user)
    {
        $notifications = $this->findNotificationsBySubscriber($user);
        $entityManager = $this->objectManager;
        $notifications->map(
            function (NotificationInterface $n) use ($entityManager) {
                if (!$n->getSeen()) {
                    $n->setSeen(true);
                    $entityManager->persist($n);
                }
            }
        );
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