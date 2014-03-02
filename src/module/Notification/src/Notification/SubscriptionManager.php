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
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

class SubscriptionManager implements SubscriptionManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;

    public function findSubscriptionsByUuid(UuidInterface $uuid)
    {
        $className     = $this->getClassResolver()->resolveClassName('Notification\Entity\SubscriptionInterface');
        $criteria      = ['object' => $uuid->getId()];
        $subscriptions = $this->getObjectManager()->getRepository($className)->findBy($criteria);
        $collection    = new ArrayCollection($subscriptions);
        return $collection;
    }

    public function isUserSubscribed(UserInterface $user, UuidInterface $object)
    {
        $className    = $this->getClassResolver()->resolveClassName('Notification\Entity\SubscriptionInterface');
        $criteria     = [
            'user'   => $user->getId(),
            'object' => $object->getId()
        ];
        $subscription = $this->getObjectManager()->getRepository($className)->findOneBy($criteria);
        return is_object($subscription);
    }

    public function subscribe(UserInterface $user, UuidInterface $object, $notifyMailman)
    {
        if (!$this->isUserSubscribed($user, $object)) {
            $class = $this->getClassResolver()->resolveClassName('Notification\Entity\SubscriptionInterface');
            /* @var $entity \Notification\Entity\SubscriptionInterface */
            $entity = new $class();
            $entity->setSubscriber($user);
            $entity->setSubscribedObject($object);
            $entity->setNotifyMailman($notifyMailman === true);
            $this->getObjectManager()->persist($entity);
        }
    }

    public function hasSubscriptions()
    {
        $className     = $this->getClassResolver()->resolveClassName('Notification\Entity\SubscriptionInterface');
        $subscriptions = $this->getObjectManager()->getRepository($className)->findBy([], null, 1);
        return count($subscriptions) > 0;
    }
}
