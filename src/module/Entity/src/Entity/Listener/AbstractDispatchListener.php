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
namespace Entity\Listener;

use Common\Listener\AbstractSharedListenerAggregate;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;
use Entity\Controller\AbstractController;
use Zend\Mvc\Router\RouteMatch;

abstract class AbstractDispatchListener extends AbstractSharedListenerAggregate
{
	/* (non-PHPdoc)
     * @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared (\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $events->attach($this->getMonitoredClass(), MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
    }

    public function onDispatch(Event $event)
    {
        $controller = $event->getTarget();
        if ($controller instanceof AbstractController) {
            /* @var $controller AbstractController */
            
            if ($controller->getEntityService()->hasPlugin('learningResource')) {
                $subject = $controller->getEntityService()
                    ->learningResource()
                    ->getDefaultSubject()
                    ->getSlug();
                
                if ($subject !== NULL) {
                    $routeMatch = new RouteMatch(array(
                        'subject' => $subject
                    ));
                    $routeMatch->setMatchedRouteName('subject');
                    $controller->getServiceLocator()
                        ->get('Ui\Navigation\DefaultNavigationFactory')
                        ->setRouteMatch($routeMatch);
                }
            }
        }
    }
}