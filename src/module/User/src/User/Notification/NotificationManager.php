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
namespace User\Notification;

use User\Notification\Entity\NotificationLogInterface;
use Event\Entity\EventLogInterface;
use User\Notification\Collection\NotificationCollection;
use Doctrine\Common\Collections\ArrayCollection;

class NotificationManager implements NotificationManagerInterface
{
    use \Common\Traits\InstanceManagerTrait,\Common\Traits\ObjectManagerAwareTrait;
    
    public function createNotification(\User\Entity\UserInterface $user, EventLogInterface $log)
    {
        $notification = $this->aggregateNotification($user, $log);
        
        // TODO aggregation
        
        $className = $this->getClassResolver()->resolveClassName('User\Notification\Entity\NotificationEventInterface');
        /* @var $notificationLog \User\Notification\Entity\NotificationEventInterface */
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
    
    public function getNotificationService(Entity\NotificationInterface $notification)
    {
        if (! $this->hasInstance($notification->getId())) {
            $this->addInstance($notification->getId(), $this->createService($notification));
        }
        return $this->getInstance($notification->getId());
    }
    
    public function findNotificationsBySubsriber(\User\Service\UserServiceInterface $userService)
    {
        $notifications = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('User\Notification\Entity\NotificationInterface'))
            ->findBy(array(
            'user' => $userService->getId()
        ));
        $collection = new ArrayCollection($notifications);
        return new NotificationCollection($collection, $this);
    }

    /**
     *
     * @param \User\Entity\UserInterface $user            
     * @param NotificationLogInterface $log            
     * @return \User\Notification\Entity\NotificationInterface
     */
    protected function aggregateNotification(\User\Entity\UserInterface $user, EventLogInterface $log)
    {
        /*$query = $this->getObjectManager()->createQuery(sprintf('SELECT n FROM %s n JOIN %s ne WITH (ne.notification = n.id) JOIN %s nl WITH (nl.id = ne.eventLog) WHERE n.user = %d AND nl.object = %d ORDER BY n.id DESC',
            $this->getClassResolver()->resolveClassName('User\Notification\Entity\NotificationInterface'),
            $this->getClassResolver()->resolveClassName('User\Notification\Entity\NotificationEventInterface'),
            $this->getClassResolver()->resolveClassName('User\Notification\Entity\NotificationLogInterface'),
            $user->getId(),
            $log->getObject()->getId()));
        $result = $query->getResult();
        if (count($result)) {
            return $result[0];
        }*/
        $className = $this->getClassResolver()->resolveClassName('User\Notification\Entity\NotificationInterface');
        return new $className();
    }

    protected function createService(Entity\NotificationInterface $notification)
    {
        $instance = $this->createInstance('User\Notification\Service\NotificationServiceInterface');
        $instance->setNotification($notification);
        return $instance;
    }
}