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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Uuid\Entity\UuidInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidHolder;

class SubscriptionManager implements SubscriptionManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait;
    
    /*
     * (non-PHPdoc) @see \User\Notification\SubscriptionManagerInterface::findSubscribersByUuid()
     */
    public function findSubscribersByUuid(\Uuid\Entity\UuidInterface $uuid)
    {
        $subscriptions = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('User\Notification\Entity\SubscriptionInterface'))
            ->findBy(array(
            'object' => $uuid->getId()
        ));
        
        $collection = new ArrayCollection();
        $this->hydrate($collection, $subscriptions);
        return $collection;
    }

    public function isUserSubscribed(UserInterface $user, UuidInterface $object)
    {
        return is_object($this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('User\Notification\Entity\SubscriptionInterface'))
            ->findOneBy(array(
            'user' => $user->getId(),
            'object' => $object->getId()
        )));
    }

    public function subscribe(UserInterface $user, UuidInterface $object, $notifyMailman)
    {
        if(!$this->isUserSubscribed($user, $object)){
            $class = $this->getClassResolver()->resolveClassName('User\Notification\Entity\SubscriptionInterface');
            /* @var $entity \User\Notification\Entity\SubscriptionInterface */
            $entity = new $class();
            $entity->setSubscriber($user);
            $entity->setSubscribedObject($object);
            $entity->setNotifyMailman($notifyMailman === TRUE);
            $this->getObjectManager()->persist($entity);
        }
        return $this;
    }

    private function hydrate(Collection $collection, array $subscriptions)
    {
        foreach ($subscriptions as $subscription) {
            /* @var $subscription Entity\SubscriptionInterface */
            $collection->add($subscription->getSubscriber());
        }
    }
}