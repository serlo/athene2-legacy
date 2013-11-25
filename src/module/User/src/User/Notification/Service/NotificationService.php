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

use Event\Collection\EventCollection;

class NotificationService implements NotificationServiceInterface
{
    use \Event\EventManagerAwareTrait;

    /**
     *
     * @var \User\Notification\Entity\NotificationInterface
     */
    protected $notification;
    
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::setNotification()
     */
    public function setNotification(\User\Notification\Entity\NotificationInterface $notification)
    {
        $this->notification = $notification;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::getNotification()
     */
    public function getNotification()
    {
        return $this->notification;
    }
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::getSeen()
     */
    public function getSeen()
    {
        return $this->getNotification()->getSeen();
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::getEventName()
     */
    public function getEventName()
    {
        return $this->getNotification()->getEventName();
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::getUser()
     */
    public function getUser()
    {
        return $this->getNotification()->getUser();
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::getEvents()
     */
    public function getEvents()
    {
        return new EventCollection($this->getNotification()->getEvents(), $this->getEventManager());
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::getActors()
     */
    public function getActors()
    {
        return $this->getNotification()->getActors();
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::getObjects()
     */
    public function getObjects()
    {
        return $this->getNotification()->getObjects();
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::getParameters()
     */
    public function getParameters()
    {
        return $this->getNotification()->getParameters();
    }
    
    /*
     * (non-PHPdoc) @see \User\Notification\Service\NotificationServiceInterface::getTimestamp()
     */
    public function getTimestamp()
    {
        return $this->getNotification()->getTimestamp();
    }

    public function setTimestamp($timestamp)
    {
        $this->getNotification->setTimestamp($timestamp);
    }
}