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
namespace User\Notification\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Notification\NotificationWorker;

class WorkerController extends AbstractActionController
{

    /**
     *
     * @var NotificationWorker
     */
    protected $notificationWorker;

    /**
     *
     * @return NotificationWorker $notificationWorker
     */
    public function getNotificationWorker()
    {
        return $this->notificationWorker;
    }

    /**
     *
     * @param NotificationWorker $notificationWorker            
     * @return self
     */
    public function setNotificationWorker(NotificationWorker $notificationWorker)
    {
        $this->notificationWorker = $notificationWorker;
        return $this;
    }

    public function runAction()
    {
        $this->getNotificationWorker()->run();
        $this->getNotificationWorker()
            ->getObjectManager()
            ->flush();
        return 'worker successfull';
    }
}