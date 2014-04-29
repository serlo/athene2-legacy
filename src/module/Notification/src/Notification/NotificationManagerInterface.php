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

use Common\ObjectManager\Flushable;
use Doctrine\Common\Collections\ArrayCollection;
use Event\Entity\EventLogInterface;
use Notification\Entity\NotificationInterface;
use User\Entity\UserInterface;

interface NotificationManagerInterface extends Flushable
{

    /**
     * @param UserInterface     $user
     * @param EventLogInterface $eventLog
     * @return NotificationInterface
     */
    public function createNotification(UserInterface $user, EventLogInterface $eventLog);

    /**
     * @param UserInterface $user
     * @return ArrayCollection
     */
    public function findNotificationsBySubscriber(UserInterface $user);

    /**
     * @param UserInterface $user
     * @return void
     */
    public function markRead(UserInterface $user);
}