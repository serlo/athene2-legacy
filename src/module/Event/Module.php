<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Event;

class Module
{

    public static $listeners = [
        'Event\Listener\RepositoryManagerListener',
        'Event\Listener\DiscussionManagerListener',
        'Event\Listener\TaxonomyManagerListener',
        'Event\Listener\UuidManagerListener',
        'Event\Listener\LinkServiceListener',
        'Event\Listener\EntityManagerListener',
        'Event\Listener\LicenseManagerListener'
    ];

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

    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        foreach (static::$listeners as $listener) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $listener       = $serviceManager->get($listener);
            $e->getApplication()->getEventManager()->getSharedManager()->attachAggregate($listener);
        }
    }
}