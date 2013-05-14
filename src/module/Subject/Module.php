<?php
namespace Subject;

class Module
{

    protected $subModules = array(
        'Math'
    );

    public function getConfig()
    {
        $return = include __DIR__ . '/config/module.config.php';
        foreach ($this->subModules as $subModule) {
            $config = include __DIR__ . '/config/'.$subModule.'.config.php';
            $return = array_merge($return, $config);
        }
        return $return;
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
}