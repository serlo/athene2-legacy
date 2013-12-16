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

    public static $listeners = [
        /*'Entity\Plugin\Link\Listener\EntityControllerListener',
        'Entity\Plugin\Repository\Listener\EntityControllerListener',
        'Entity\Plugin\Taxonomy\Listener\EntityControllerListener',
        'Entity\Plugin\Pathauto\Listener\RepositoryControllerListener',
        'Entity\Plugin\LearningResource\Listener\EntityControllerListener',
        'Entity\Plugin\LearningResource\Listener\EntityTaxonomyPluginControllerListener',
        'Entity\Plugin\Link\Listener\LinkControllerListener',
        'Entity\Plugin\Repository\Listener\RepositoryControllerListener',
        'Entity\Plugin\Taxonomy\Listener\TaxonomyControllerListener'*/
    ];

    public 

    function getConfig()
    {
        $include = [
            'module',
            'route'
        ];
        $config = [];
        
        foreach ($include as $file) {
            $config = ArrayUtils::merge($config, include __DIR__ . '/config/' . $file . '.config.php');
        }
        return $config;
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