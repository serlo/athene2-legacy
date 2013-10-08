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

return array(
    'class_resolver' => array(
        'Event\Entity\EventLogInterface' => 'Event\Entity\EventLog',
        'Event\Entity\EventInterface' => 'Event\Entity\Event',
        'Event\Entity\EventStringInterface' => 'Event\Entity\EventString'
    ),
    'service_manager' => array(
        'factories' => array(
            __NAMESPACE__ . '\EventManager' => function ($sm)
            {
                $eventManager = new \Event\EventManager();
                $config = $sm->get('config')->get('event_manager');
                
                $eventManager->setConfig($config);
                $eventManager->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                $eventManager->setObjectManager($sm->get('EntityManager'));
                $eventManager->setSharedEventManager($sm->get('SharedEventManager'));
                return $eventManager;
            }
        )
    ),
    'user_manager' => array(
        'listeners' => array(
            __NAMESPACE__ . '\Listener\Event\UserForwardingListener'
        )
    ),
    'di' => array(
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\EventManagerInterface' => __NAMESPACE__ . '\EventManager'
            )
        )
    ),
    'event_manager' => array(
        'listeners' => array(
            __NAMESPACE__ . '\Listener\Event\UserForwardingListener'
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);

