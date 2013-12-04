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
        'Entity\Plugin\License\Listener\EntityControllerListener',
        'Entity\Plugin\Repository\Listener\EntityControllerListener',
        'Entity\Plugin\Taxonomy\Listener\EntityControllerListener',
        'Entity\Plugin\Pathauto\Listener\RepositoryControllerListener',
        'Entity\Plugin\LearningResource\Listener\EntityControllerListener',
        'Entity\Plugin\LearningResource\Listener\EntityTaxonomyPluginControllerListener',
        'Entity\Plugin\Link\Listener\LinkControllerListener',
        'Entity\Plugin\Page\Listener\PageControllerListener',
        'Entity\Plugin\Repository\Listener\RepositoryControllerListener',
        'Entity\Plugin\Taxonomy\Listener\TaxonomyControllerListener'
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
        $application = $e->getApplication();
        $eventManager = $application->getEventManager();
        
        foreach (self::$listeners as $listener) {
            $eventManager->getSharedManager()->attachAggregate($e->getApplication()
                ->getServiceManager()
                ->get($listener));
        }
    }
}