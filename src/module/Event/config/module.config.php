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

use Event\View\Helper\EventLog;
return array(
    'event_manager' => array(),
    'class_resolver' => array(
        'Event\Entity\EventLogInterface' => 'Event\Entity\EventLog',
        'Event\Entity\EventInterface' => 'Event\Entity\Event',
        'Event\Entity\EventParameterInterface' => 'Event\Entity\EventParameter',
        'Event\Entity\EventParameterNameInterface' => 'Event\Entity\EventParameterName',
        'Event\Service\EventServiceInterface' => 'Event\Service\EventService'
    ),
    'view_helpers' => array(
        'factories' => array(
            'events' => function ($sm)
            {
                $instance = new EventLog();
                $instance->setEventManager($sm->getServiceLocator()
                    ->get('Event\EventManager'));
                return $instance;
            }
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\EventController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\EventManager' => array(
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Service\EventService' => array(
                    'setUuidManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Listener\UserControllerListener' => array(
                    'setEventManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Listener\TaxonomyTermControllerListener' => array(
                    'setEventManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Listener\DiscussionControllerListener' => array(
                    'setEventManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Listener\EntityControllerListener' => array(
                    'setEventManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Listener\UuidControllerListener' => array(
                    'setEventManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Listener\RepositoryPluginControllerListener' => array(
                    'setEventManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\EventManagerInterface' => __NAMESPACE__ . '\EventManager'
            ),
            __NAMESPACE__ . '\Service\EventService' => array(
                'shared' => false
            )
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
    ),
    'router' => array(
        'routes' => array(
            'event' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/event',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\EventController'
                    )
                ),
                'child_routes' => array(
                    'history' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/history/:id',
                            'defaults' => array(
                                'action' => 'history'
                            )
                        )
                    )
                )
            )
        )
    )
);