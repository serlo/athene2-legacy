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

trait NotificationManagerAwareTrait
{

    /**
     * @var NotificationManagerInterface
     */
    protected $notificationManager;

    /**
     * @return NotificationManagerInterface $notificationManager
     */
    public function getNotificationManager()
    {
        return $this->notificationManager;
    }

    /**
     * @param NotificationManagerInterface $notificationManager
     * @return self
     */
    public function setNotificationManager(NotificationManagerInterface $notificationManager)
    {
        $this->notificationManager = $notificationManager;

        return $this;
    }
}