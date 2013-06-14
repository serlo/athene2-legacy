<?php
namespace Math;

class Module
{

    public function getConfig ()
    {
        return include __DIR__ . 'Math/config/module.config.php';
    }

    public function getAutoloaderConfig ()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . 'Math/src/' . __NAMESPACE__
                )
            )
        );
    }
}