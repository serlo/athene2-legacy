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
namespace User\Notification\Entity;

use \Event\Entity;

interface NotificationEventInterface
{

    /**
     *
     * @return NotificationLogInterface
     */
    public function getEventLog();

    /**
     *
     * @param NotificationLogInterface $eventLog            
     * @return $this;
     */
    public function setEventLog(NotificationLogInterface $eventLog);

    /**
     *
     * @return Entity\EventInterface
     */
    public function getEvent();

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return \Uuid\Entity\Uuid
     */
    public function getObject();

    /**
     *
     * @return \User\Entity\UserInterface
     */
    public function getActor();

    /**
     *
     * @return NotificationInterface
     */
    public function getNotification();

    /**
     *
     * @param NotificationInterface $notification            
     * @return $this
     */
    public function setNotification(NotificationInterface $notification);
}