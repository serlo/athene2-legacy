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

use User\Collection\UserCollection;
use Uuid\Entity\UuidInterface;
use User\Service\UserServiceInterface;
use Uuid\Entity\UuidHolder;

interface SubscriptionManagerInterface
{

    /**
     *
     * @param UuidInterface $uuid            
     * @return UserCollection
     */
    public function findSubscribersByUuid(UuidInterface $uuid);
    
    /**
     * 
     * @param UserServiceInterface $user
     * @param UuidInterface $object
     * @param bool $notifyMailman
     * @return $this
     */
    public function subscribe(UserServiceInterface $user, UuidInterface $object, $notifyMailman);
    public function isUserSubscribed(UserServiceInterface $user, UuidInterface $object);
}