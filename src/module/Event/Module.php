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
namespace Event;

class Module
{

    protected $listeners = array(
        'Event\Listener\UserControllerListener',
        'Event\Listener\RepositoryPluginControllerListener',
        'Event\Listener\DiscussionControllerListener'
    );

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

    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        foreach ($this->listeners as $listener) {
            $e->getApplication()
                ->getEventManager()
                ->getSharedManager()
                ->attachAggregate($e->getApplication()
                ->getServiceManager()
                ->get($listener));
        }
    }
}