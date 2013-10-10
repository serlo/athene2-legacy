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
namespace User\Notification\Listener;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\SharedListenerAggregateInterface;
use User\Service\UserServiceInterface;
use Entity\Service\EntityServiceInterface;
use Uuid\Entity\UuidInterface;

abstract class AbstractListener implements SharedListenerAggregateInterface
{
    use\User\Notification\NotificationLogManagerAwareTrait;

    public function logEvent(AbstractActionController $controller, UserServiceInterface $actor, EntityServiceInterface $uuid, UuidInterface $reference = NULL)
    {
        $this->getNotificationLogManager()->logEvent($controller->getEvent()
            ->getRouteMatch()
            ->getMatchedRouteName(), $actor->getEntity(), $uuid->getEntity(), $reference);
    }
}