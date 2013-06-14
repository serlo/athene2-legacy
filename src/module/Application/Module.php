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
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        // Load translator
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        // Load Subjects
        $app      = $e->getTarget();
        $serviceManager       = $app->getServiceManager();
        $listener = $serviceManager->get('Subject\Hydrator\Route');
        $listener->setPath(__DIR__ . '/config/subject/');
        $app->getEventManager()->attach('route', array($listener, 'onPreRoute'), 5);
    }

    public function getConfig()
    {
        $config = array_merge_recursive(
            include __DIR__ . '/config/module.config.php',
            include __DIR__ . '/config/subject/module.config.php',
            include __DIR__ . '/config/learning-object/module.config.php'
        );
        return $config; 
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
