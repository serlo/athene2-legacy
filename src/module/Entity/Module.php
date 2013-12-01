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
namespace Entity;

use Zend\Stdlib\ArrayUtils;

class Module
{

    public static $listeners = array(
        'Entity\Plugin\Link\Listener\EntityControllerListener',
        'Entity\Plugin\Repository\Listener\EntityControllerListener',
        'Entity\Plugin\Taxonomy\Listener\EntityControllerListener',
        'Entity\Plugin\Pathauto\Listener\RepositoryControllerListener',
        'Entity\Plugin\LearningResource\Listener\EntityControllerListener',
        'Entity\Plugin\LearningResource\Listener\EntityTaxonomyPluginControllerListener'
    );

    public function getConfig()
    {
        $module = include __DIR__ . '/config/module.config.php';
        $types = include __DIR__ . '/config/plugins/plugins.config.php';
        $plugins = include __DIR__ . '/config/types/types.config.php';
        $merge = ArrayUtils::merge($module, $types);
        $merge = ArrayUtils::merge($merge, $plugins);
        return $merge;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        foreach (self::$listeners as $listener) {
            $e->getApplication()
                ->getEventManager()
                ->getSharedManager()
                ->attachAggregate($e->getApplication()
                ->getServiceManager()
                ->get($listener));
        }
    }
}