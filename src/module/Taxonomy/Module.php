<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace Taxonomy;

/**
 * @codeCoverageIgnore
 */
class Module
{

    public static $listeners = [
        'Taxonomy\Listener\EntityManagerListener',
    ];

    public function getConfig ()
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
        $application = $e->getApplication();
        $eventManager = $application->getEventManager();
        
        foreach (self::$listeners as $listener) {
            $eventManager->getSharedManager()->attachAggregate($e->getApplication()
                ->getServiceManager()
                ->get($listener));
        }
    }
}

