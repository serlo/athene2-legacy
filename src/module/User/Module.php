<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User;

use Zend\Mvc\MvcEvent;

class Module
{

    public static $listeners = [
        'User\Notification\Listener\RepositoryManagerListener',
        'User\Notification\Listener\DiscussionControllerListener'
    ];

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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

    public function onBootstrap(MvcEvent $e)
    {
        foreach (static::$listeners as $listener) {
            $e->getApplication()
                ->getEventManager()
                ->getSharedManager()
                ->attachAggregate($e->getApplication()
                ->getServiceManager()
                ->get($listener));
        }
    }
}