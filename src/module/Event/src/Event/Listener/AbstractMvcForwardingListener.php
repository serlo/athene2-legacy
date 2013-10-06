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
namespace Event\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Uuid\Entity\UuidHolder;
use User\Entity\UserInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Language\Entity\LanguageInterface;

abstract class AbstractMvcForwardingListener implements ListenerAggregateInterface
{
    use \Event\EventManagerAwareTrait;
    
    public function logEvent(AbstractActionController $controller, LanguageInterface $language, UserInterface $actor, UuidHolder $uuid, $object, $verb)
    {
        $this->getEventManager()->logEvent($controller->getEvent()->getRouteMatch()->getMatchedRouteName(), $language, $actor, $uuid, $object, $verb);
    }
}