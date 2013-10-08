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

/**
 * @TODO Undirtyfy
 */
class NotificationWorker
{
    use\Common\Traits\ObjectManagerAwareTrait,\User\Manager\UserManagerAwareTrait, SubscriptionManagerAwareTrait, NotificationManagerAwareTrait;

    public function run()
    {
        foreach ($this->getWorkload() as $eventLog) {
            /* @var $eventLog \Event\Entity\EventLogInterface */
            foreach ($this->getSubscriptionManager()->findSubscribersByUuid($eventLog->getUuid()) as $subscriber) {
                /* @var $subscriber \User\Entity\UserInterface */
                $this->getNotificationManager()->createNotification($subscriber, $eventLog);
            }
        }
    }

    protected function getWorkload(){
        $offset = $this->findOffset();
        $query = $this->getObjectManager()->createQuery(sprintf('SELECT ne FROM Event\Entity\EventLog e WHERE e.id > %d ORDER BY e.id ASC;', $offset));
        return $query->getResult();
    }

    private function findOffset(){
        $query = $this->getObjectManager()->createQuery('SELECT ne FROM Entity\NotificationEvent ne ORDER BY ne.event_log_id DESC');
        $results = $query->getResult();
        if(count($results)){
            /* @var $result Entity\NotificationEventInterface */
            $result = $results[0];
            return $result->getEventLog()->getId();
        }
        return 0;
    }
}