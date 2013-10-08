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

class NotificationManager implements NotificationManagerInterface
{
    use\Common\Traits\InstanceManagerTrait, \Common\Traits\ObjectManagerAwareTrait;
    
    /*
     * (non-PHPdoc) @see \User\Notification\NotificationManagerInterface::createNotification()
     */
    public function createNotification(\User\Entity\UserInterface $user, \Event\Entity\EventLogInterface $eventLog)
    {
        $className = $this->getClassResolver()->resolveClassName('User\Notification\Entity\NotificationInterface');
        $notification = 
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\NotificationManagerInterface::getNotification()
     */
    public function getNotificationService(Entity\NotificationInterface $notification)
    {
        if (! $this->hasInstance($notification->getId())) {
            $this->addInstance($notification->getId(), $this->createService($notification));
        }
        return $this->getInstance($notification->getId());
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\NotificationManagerInterface::findNotificationsBySubsriber()
     */
    public function findNotificationsBySubsriber(\User\Service\UserServiceInterface $userService)
    {
        // TODO Auto-generated method stub
    }

    protected function createService(Entity\NotificationInterface $notification)
    {
        $instance = $this->createInstance('User\Notification\Service\NotificationServiceInterface');
        $instance->setNotification($notification);
        return $instance;
    }
}