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

use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

class NotificationWorker
{
    use\Common\Traits\ObjectManagerAwareTrait, \User\Manager\UserManagerAwareTrait, SubscriptionManagerAwareTrait,
        NotificationManagerAwareTrait, \ClassResolver\ClassResolverAwareTrait;

    /**
     * @TODO Undirtyfy
     */
    public function run()
    {
        if (!$this->getSubscriptionManager()->hasSubscriptions()) {
            return;
        }

        /* @var $eventLog \Event\Entity\EventLogInterface */
        foreach ($this->getWorkload() as $eventLog) {
            /* @var $subscriptions UserInterface[] */
            $object        = $eventLog->getObject();
            $subscriptions = $this->getSubscriptionManager()->findSubscriptionsByUuid($object);
            $subscribed    = [];

            foreach ($subscriptions as $subscription) {
                // Don't create notifications for myself
                if ($subscription->getSubscriber() !== $eventLog->getActor() && $eventLog->getTimestamp(
                    ) > $subscription->getTimestamp()
                ) {
                    $this->getNotificationManager()->createNotification($subscription->getSubscriber(), $eventLog);
                    $subscribed[] = $subscription->getSubscriber();
                }
            }

            foreach ($eventLog->getParameters() as $parameter) {
                if ($parameter->getValue() instanceof UuidInterface) {
                    /* @var $subscribers UserInterface[] */
                    $object        = $parameter->getValue();
                    $subscriptions = $this->getSubscriptionManager()->findSubscriptionsByUuid($object);

                    foreach ($subscriptions as $subscription) {
                        if (!in_array($subscription->getSubscriber(), $subscribed) && $subscription->getSubscriber(
                            ) !== $eventLog->getActor() && $eventLog->getTimestamp() > $subscription->getTimestamp()
                        ) {
                            $this->getNotificationManager()->createNotification(
                                $subscription->getSubscriber(),
                                $eventLog
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * @TODO Undirtyfy
     */
    protected function getWorkload()
    {
        $offset = $this->findOffset();
        $query  = $this->getObjectManager()->createQuery(
            sprintf(
                'SELECT el FROM %s el WHERE el.id > %d ORDER BY el.id ASC',
                $this->getClassResolver()->resolveClassName('Event\Entity\EventLogInterface'),
                $offset
            )
        );

        return $query->getResult();
    }

    /**
     * @TODO Undirtyfy
     */
    private function findOffset()
    {
        $query   = $this->getObjectManager()->createQuery(
            sprintf(
                'SELECT ne FROM %s ne ORDER BY ne.eventLog DESC',
                $this->getClassResolver()->resolveClassName('Notification\Entity\NotificationEventInterface')
            )
        );
        $results = $query->getResult();
        if (count($results)) {
            /* @var $result Entity\NotificationEventInterface */
            $result = $results[0];

            return $result->getEventLog()->getId();
        }

        return 0;
    }
}