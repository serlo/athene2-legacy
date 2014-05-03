<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Entity\Listener;

use Common\Listener\AbstractSharedListenerAggregate;
use Entity\Controller\AbstractController;
use Navigation\Factory\DefaultNavigationFactoryFactory;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

abstract class AbstractDispatchListener extends AbstractSharedListenerAggregate
{
    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach(
            $this->getMonitoredClass(),
            MvcEvent::EVENT_DISPATCH,
            [
                $this,
                'onDispatch'
            ]
        );
    }

    public function onDispatch(Event $event)
    {
        $controller = $event->getTarget();
        if (!$controller instanceof AbstractController) {
            return;
        }

        $entity = $controller->getEntity();
        if (!$entity) {
            return;
        }

        $subject = null;

        foreach ($entity->getTaxonomyTerms() as $term) {
            $subject = $term->findAncestorByTypeName('subject');
            if ($subject) {
                break;
            }
        }

        if ($subject !== null) {
            /* @var $navigationFactory DefaultNavigationFactoryFactory */
            $navigationFactory = $controller->getServiceLocator()->get(
                'Navigation\Factory\DefaultNavigationFactory'
            );
            $params            = ['term' => $subject->getId()];
            $routeMatch        = new RouteMatch($params);

            $routeMatch->setMatchedRouteName('taxonomy/term/get');
            $navigationFactory->setRouteMatch($routeMatch);
        }
    }
}
