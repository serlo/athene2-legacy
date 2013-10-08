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

use User\Service\UserServiceInterface;
use User\Entity\UserInterface;
use Event\Entity\EventLogInterface;
use Doctrine\Common\Collections\ArrayCollection;

interface NotificationManagerInterface
{

    /**
     *
     * @param UserInterface $user            
     * @param EventLogInterface $eventLog            
     * @return $this
     */
    public function createNotification(UserInterface $user, EventLogInterface $eventLog);

    /**
     *
     * @param int $id            
     * @return Service\NotificationServiceInterface
     */
    public function getNotificationService($id);

    /**
     *
     * @param UserServiceInterface $userService            
     * @return ArrayCollection
     */
    public function findNotificationsBySubsriber(UserServiceInterface $userService);
}