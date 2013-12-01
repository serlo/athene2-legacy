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
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ZfcRbac\Service\Guard as ZfcRbac;
use Zend\Mvc\Router\RouteInterface;
use Zend\Http\Request;
use ZfcRbac\Guard\GuardPluginManager;
use Zend\Mvc\Router\RouteMatch;
use ZfcRbac\Guard\GuardInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Application;

class Guard extends AbstractHelper
{

    protected $guards = [
        'ZfcRbac\Guard\ControllerGuard',
        'ZfcRbac\Guard\RouteGuard'
    ];

    /**
     *
     * @var RouteInterface
     */
    protected $router;

    /**
     *
     * @var Application
     */
    protected $application;

    /**
     *
     * @var GuardPluginManager
     */
    protected $guardPluginManager;

    /**
     *
     * @return GuardPluginManager $setGuardPluginManager
     */
    public function getGuardPluginManager()
    {
        return $this->setGuardPluginManager;
    }

    /**
     *
     * @return Application $application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     *
     * @param Application $application            
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     *
     * @param GuardPluginManager $setGuardPluginManager            
     * @return $this
     */
    public function setGuardPluginManager(GuardPluginManager $setGuardPluginManager)
    {
        $this->setGuardPluginManager = $setGuardPluginManager;
        return $this;
    }

    /**
     *
     * @return RouteInterface $router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     *
     * @param RouteInterface $router            
     * @return $this
     */
    public function setRouter(RouteInterface $router)
    {
        $this->router = $router;
        return $this;
    }

    public function __invoke()
    {
        return $this;
    }

    public function hasAccessToUrl($url)
    {
        $request = new Request();
        $request->setUri($url);
        
        $routeMatch = $this->getRouter()->match($request);
        
        $event = $this->fakeMvcEvent();
        $event = $this->controllerToMvcEvent($routeMatch->getParam('controller', ''), $routeMatch->getParam('action', ''), $event);
        $event = $this->routeToMvcEvent($routeMatch->getMatchedRouteName(), $event);
        
        return $this->hasAccess($event);
    }

    protected function controllerToMvcEvent($controller, $action, MvcEvent $event = NULL)
    {
        if ($event === NULL) {
            $event = $this->fakeMvcEvent();
        }
        $routeMatch = new RouteMatch(array(
            'controller' => $controller,
            'action' => $action
        ));
        $event->setRouteMatch($routeMatch);
        return $event;
    }

    protected function routeToMvcEvent($route, MvcEvent $event = NULL)
    {
        if ($event === NULL) {
            $event = $this->fakeMvcEvent();
        }
        $event = $this->fakeMvcEvent();
        $routeMatch = new RouteMatch(array());
        $routeMatch->setMatchedRouteName($route);
        $event->setRouteMatch($routeMatch);
        return $event;
    }

    public function hasAccessToController($controller, $action)
    {
        $event = $this->controllerToMvcEvent($controller, $action);
        return $this->hasAccess($event);
    }

    public function hasAccessToRoute($route)
    {
        $event = $this->routeToMvcEvent($route);
        return $this->hasAccess($event);
    }

    public function hasAccess(MvcEvent &$event)
    {
        foreach ($this->guards as $name) {
            
            /* @var $guard GuardInterface */
            $granted = $this->getGuardPluginManager()
                ->get($name)
                ->isGranted($event);
            if (!$granted) {
                unset($event);
                return false;
            }
        }
        unset($event);
        return true;
    }

    /**
     *
     * @return \Zend\Mvc\MvcEvent
     */
    protected function fakeMvcEvent()
    {
        return clone $this->getApplication()->getMvcEvent();
    }
}