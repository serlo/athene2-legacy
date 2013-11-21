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

class NotificationService implements NotificationServiceInterface
{

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
}