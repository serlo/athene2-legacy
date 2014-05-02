<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Jakob Pfab (jakob.pfab@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Cache;

use StrokerCache\Event\CacheEvent;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

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

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $cacheService   = $serviceManager->get('strokercache_service');
        $userManager    = $serviceManager->get('User\Manager\UserManager');
        $cacheService->getEventManager()->attach(
            CacheEvent::EVENT_LOAD,
            function (CacheEvent $e) use ($userManager) {
                if (is_object($userManager->getUserFromAuthenticator())) {
                    $e->stopPropagation(true);
                    return false;
                }
            },
            1000
        );
        $cacheService->getEventManager()->attach(
            CacheEvent::EVENT_SHOULDCACHE,
            function (CacheEvent $e) use ($userManager) {
                if (is_object($userManager->getUserFromAuthenticator())) {
                    $e->stopPropagation(true);
                    return false;
                }
            },
            1000
        );
        $cacheService->getEventManager()->attach(
            CacheEvent::EVENT_SAVE,
            function (CacheEvent $e) use ($userManager) {
                if (is_object($userManager->getUserFromAuthenticator())) {
                    $e->stopPropagation(true);
                    return false;
                }
            },
            1000
        );
    }
}