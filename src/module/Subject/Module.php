<?php
namespace Subject;

use Zend\Mvc\MvcEvent;
// use Zend\Mvc\ModuleRouteListener;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    

    public function onBootstrap(MvcEvent $e)
    {
        $app      = $e->getTarget();
        $serviceManager       = $app->getServiceManager();
    
        // Load Subjects
        //$listener = $serviceManager->get('Subject\Hydrator\Route');
        //$listener->setPath(__DIR__ . '/config/subject/');
        //$app->getEventManager()->attach('route', array($listener, 'onPreRoute'), 5);
    
        // Route translator
        //$app->getEventManager()->attach('route', array($this, 'onPreRoute'), 4);
        
        // Load Subjects
        /*$listener = $serviceManager->get('Subject\Hydrator\Route');
        $listener->setPath(__DIR__ . '/config/subject/');
        $app->getEventManager()->attach('route', array($listener, 'onPreRoute'), 5);*/
    
        $hydrator = $serviceManager->get('Subject\Hydrator\Navigation');
        $hydrator->setPath(__DIR__ . '/config/navigation/');
    }
    

    public function getAutoloaderConfig()
    {
        $namespaces = array(
        );
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    
    /*public function onBootstrap($e)
    {
        $app      = $e->getTarget();
        $serviceManager       = $app->getServiceManager();
        $listener = $serviceManager->get('Subject\Hydrator\Route');
        $app->getEventManager()->attach('route', array($listener, 'onPreRoute'), 5);
    }*/
}