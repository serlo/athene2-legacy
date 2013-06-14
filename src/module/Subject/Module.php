<?php
namespace Subject;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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