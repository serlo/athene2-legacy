<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Alias;

use Common\Router\Slashable;
use Exception;
use Zend\Console\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class Module
{

    public static $listeners = [
        'Alias\Listener\BlogManagerListener',
        'Alias\Listener\PageControllerListener',
        'Alias\Listener\RepositoryManagerListener'
    ];

    public function getAutoloaderConfig()
    {
        $autoloader = [];

        $autoloader['Zend\Loader\StandardAutoloader'] = [
            'namespaces' => [
                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
            ]
        ];

        if (file_exists(__DIR__ . '/autoload_classmap.php')) {
            return [
                'Zend\Loader\ClassMapAutoloader' => [
                    __DIR__ . '/autoload_classmap.php',
                ]
            ];

        }

        return $autoloader;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $this->registerRoute($e);
        $eventManager       = $e->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        foreach (self::$listeners as $listener) {
            $sharedEventManager->attachAggregate(
                $e->getApplication()->getServiceManager()->get($listener)
            );
        }
    }

    public function onRender(MvcEvent $e)
    {
        $response = $e->getResponse();

        if ($response instanceof Response) {
            return;
        }

        if ($response->getStatusCode() == 404) {
            /* @var $aliasManager AliasManager */
            $application     = $e->getApplication();
            $eventManager    = $application->getEventManager();
            $serviceManager  = $application->getServiceManager();
            $aliasManager    = $serviceManager->get('Alias\AliasManager');
            $instanceManager = $serviceManager->get('Instance\Manager\InstanceManager');
            /* @var $uriObject \Zend\Uri\Http */
            $uriObject = $application->getRequest()->getUri();
            $uri       = $uriObject->makeRelative('/')->getPath();
            try {
                $aliasManager->findSourceByAlias($uri, $instanceManager->getInstanceFromRequest());
            } catch (Exception $ex) {
                // We need to be doing this here, because otherwise we mess up the layout in some cases
                $e->getViewModel()->setTemplate('layout/1-col');
                return;
            }
            $response->setStatusCode(200);
            $newEvent   = clone $e;
            $routeMatch = new RouteMatch([
                'controller' => 'Alias\Controller\AliasController',
                'action'     => 'forward',
                'alias'      => $uri
            ]);
            $newEvent->setRouteMatch($routeMatch);
            $eventManager->trigger('dispatch', $newEvent);
        }
    }

    protected function registerRoute(MvcEvent $e)
    {
        $eventManager   = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        $router         = $serviceManager->get('HttpRouter');
        $route          = Slashable::factory(
            [
                'route'       => '/:alias',
                'defaults'    => [
                    'controller' => 'Alias\Controller\AliasController',
                    'action'     => 'forward'
                ],
                'constraints' => [
                    'alias' => '(.)+'
                ]
            ]
        );

        $router->addRoute('alias', $route, -10000);
        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), -1000);
    }
}
