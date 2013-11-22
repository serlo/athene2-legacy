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
namespace Subject;

use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;

class Module
{

    public function getInstanceConfig()
    {
        $dir = __DIR__ . '/config/instances';
        $instances = array(
            'de\mathe' => $dir . '/de/mathe/instance.config.php',
            'de\physik' => $dir . '/de/physik/instance.config.php',
            'de\permakultur' => $dir . '/de/permakultur/instance.config.php',
            'en\math' => $dir . '/en/math/instance.config.php',
            'en\physics' => $dir . '/en/physics/instance.config.php'
        );
        $config = array();
        foreach ($instances as $instance) {
            $config = ArrayUtils::merge($config, include $instance);
        }
        return $config;
    }

    public function getConfig()
    {
        return ArrayUtils::merge(include __DIR__ . '/config/module.config.php', $this->getInstanceConfig());
    }

    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getTarget();
        $serviceManager = $app->getServiceManager();
        
        $hydrator = $serviceManager->get('Subject\Hydrator\Navigation');
        $hydrator->setPath(__DIR__ . '/config/instances/');
    }

    public function getAutoloaderConfig()
    {
        $namespaces = array();
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    protected static function getInstanceConfigs()
    {
        $return = array();
        $path = self::findParentPath('config/instances');
        $dir = __DIR__ . '/';
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                echo $dir . $file . is_dir($dir . $file . '/') . '<br/>';
                flush();
                if ($file != "." && $file != ".." && is_dir($dir . $file)) {
                    $return = ArrayUtils::merge($return, include $dir . $file . '/instance.config.php');
                }
            }
        }
        return $return;
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__ . '/';
        $previousDir = '.';
        while (! is_dir($dir . $path) && ! file_exists($dir . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir)
                return false;
            $previousDir = $dir;
        }
        return $dir . $path;
    }
}