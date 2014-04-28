<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Notification;

use Notification\Entity\SubscriptionInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

interface SubscriptionManagerInterface
{

    /**
     * @param UuidInterface $uuid
     * @return SubscriptionInterface[]
     */
    public function findSubscriptionsByUuid(UuidInterface $uuid);

    public function hasSubscriptions();

    public function isUserSubscribed(UserInterface $user, UuidInterface $object);

    /**
     * @param UserInterface $user
     * @param UuidInterface $object
     * @param bool          $notifyMailman
     * @return self
     */
    public function subscribe(UserInterface $user, UuidInterface $object, $notifyMailman);
}