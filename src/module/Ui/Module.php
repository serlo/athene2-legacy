<?php
/**
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license   LGPL
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Ui;

use Zend\Stdlib\ArrayUtils;

class Module
{

    public function getAutoloaderConfig()
    {
        if (file_exists(__DIR__ . '/autoload_classmap.php')) {
            return [
                'Zend\Loader\ClassMapAutoloader' => [
                    __DIR__ . '/autoload_classmap.php',
                ]
            ];
        } else {
            return [
                'Zend\Loader\StandardAutoloader' => [
                    'namespaces' => [
                        __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                    ]
                ]
            ];
        }
    }

    public function getConfig()
    {
        $config = include __DIR__ . '/config/module.config.php';

        if (file_exists(__DIR__ . '/template_map.php')) {
            $templates                 = [];
            $templates['view_manager'] = [
                'template_map' => include __DIR__ . '/template_map.php'
            ];

            $config = ArrayUtils::merge($config, $templates);
        }

        return $config;
    }
}
