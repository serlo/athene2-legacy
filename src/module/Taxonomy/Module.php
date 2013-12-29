<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace Taxonomy;

/**
 * @codeCoverageIgnore
 */
class Module
{

    public static $listeners = [
        'Taxonomy\Listener\EntityManagerListener',
    ];

    public function getConfig ()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig ()
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

