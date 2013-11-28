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
namespace User\Notification\Service;

use User\Notification\Entity\NotificationInterface;

interface NotificationServiceInterface
{

    /**
     *
     * @return NotificationInterface
     */
    public function getNotification();

    /**
     *
     * @return bool
     */
    public function getSeen();

    /**
     *
     * @return string
     */
    public function getEventName();

    /**
     *
     * @return Entity\User
     */
    public function getUser();

    /**
     *
     * @return ArrayCollection
     */
    public function getEvents();

    /**
     *
     * @return ArrayCollection
     */
    public function getActors();

    /**
     *
     * @return ArrayCollection
     */
    public function getObjects();

    /**
     *
     * @return ArrayCollection
     */
    public function getParameters();

    /**
     *
     * @return \DateTime $date
     */
    public function getTimestamp();
    /**
     * 
     * @param mixed $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp);
}