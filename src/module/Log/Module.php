<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Log;

use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;

class Module
{

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

        $sharedEventManager->attach(
            'Zend\Mvc\Application',
            'dispatch.error',
            function (Event $e) use ($serviceLocator) {
                $exception = $e->getParam('exception');
                $serviceLocator->get('Zend\Log\Logger')->crit($exception);
            }
        );
    }
}