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
namespace Normalizer;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\Segment;
use Zend\Mvc\Router\Http\TreeRouteStack;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        $autoloader                                   = [];

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

    public function onBootstrap(MvcEvent $e)
    {
        $this->registerRoute($e);
    }

    protected function registerRoute(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $router         = $e->getRouter();
        $route          = Segment::factory(
            [
                'route'       => '/:uuid',
                'defaults'    => [
                    'controller' => __NAMESPACE__ . '\Controller\SignpostController',
                    'action'     => 'index'
                ],
                'constraints' => [
                    'alias' => '[0-9]+'
                ]
            ]
        );

        if (!$router instanceof TreeRouteStack) {
            $router = $serviceManager->get('HttpRouter');
        }

        $router->addRoute('uuid/get', $route, -9999);
    }
}