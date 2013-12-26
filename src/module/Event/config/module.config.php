<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Event;

use Event\View\Helper\EventLog;
return [
    'event_manager' => [],
    'class_resolver' => [
        'Event\Entity\EventLogInterface' => 'Event\Entity\EventLog',
        'Event\Entity\EventInterface' => 'Event\Entity\Event',
        'Event\Entity\EventParameterInterface' => 'Event\Entity\EventParameter',
        'Event\Entity\EventParameterNameInterface' => 'Event\Entity\EventParameterName',
        'Event\Service\EventServiceInterface' => 'Event\Service\EventService'
    ],
    'view_helpers' => [
        'factories' => [
            'events' => function ($sm)
            {
                $instance = new EventLog();
                $instance->setEventManager($sm->getServiceLocator()
                    ->get('Event\EventManager'));
                return $instance;
            }
        ]
    ],
    'di' => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\EventController'
        ],
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\EventManager' => [
                    'setClassResolver' => [
                        'required' => true
                    ],
                    'setObjectManager' => [
                        'required' => true
                    ],
                    'setServiceLocator' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Service\EventService' => [
                    'setUuidManager' => [
                        'required' => true
                    ],
                    'setLanguageManager' => [
                        'required' => true
                    ],
                    'setUserManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Listener\TaxonomyTermControllerListener' => [
                    'setEventManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Listener\DiscussionControllerListener' => [
                    'setEventManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Listener\LinkServiceListener' => [
                    'setEventManager' => [
                        'required' => true
                    ],
                    'setLanguageManager' => [
                        'required' => true
                    ],
                    'setUserManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Listener\EntityControllerListener' => [
                    'setEventManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Listener\UuidControllerListener' => [
                    'setEventManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Listener\RepositoryControllerListener' => [
                    'setEventManager' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\EventManagerInterface' => __NAMESPACE__ . '\EventManager'
            ],
            __NAMESPACE__ . '\Service\EventService' => [
                'shared' => false
            ]
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'router' => [
        'routes' => [
            'event' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/event',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\EventController'
                    ]
                ],
                'child_routes' => [
                    'history' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/history/:id',
                            'defaults' => [
                                'action' => 'history'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];