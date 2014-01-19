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
namespace Notification;

use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

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
     * @param UserInterface $user
     * @param UuidInterface $object
     * @param bool $notifyMailman
     * @return self
     */
    public function subscribe(UserInterface $user, UuidInterface $object, $notifyMailman);
    public function isUserSubscribed(UserInterface $user, UuidInterface $object);
}