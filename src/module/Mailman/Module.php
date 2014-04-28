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
namespace Mailman;

use Zend\Console\Request;
use Zend\Mvc\MvcEvent;
use Zend\Uri\Http;

class Module
{
    public static $listeners = [
        'Mailman\Listener\UserControllerListener',
        'Mailman\Listener\AuthenticationControllerListener',
        'Mailman\Listener\NotificationWorkerListener'
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
        $application        = $e->getApplication();
        $eventManager       = $application->getEventManager();
        $serviceLocator     = $application->getServiceManager();
        $sharedEventManager = $eventManager->getSharedManager();

        foreach (static::$listeners as $listener) {
            $sharedEventManager->attachAggregate($serviceLocator->get($listener));
        }

        if ($e->getRequest() instanceof Request) {
            /* @var $moduleOptions Options\ModuleOptions */
            $moduleOptions = $serviceLocator->get('Mailman\Options\ModuleOptions');
            $uri           = new Http($moduleOptions->getLocation());
            $serviceLocator->get('HttpRouter')->setRequestUri($uri);
        }
    }
}
