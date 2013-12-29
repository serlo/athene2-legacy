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
namespace Uuid;

return [
    'class_resolver' => [
        'Uuid\Entity\UuidInterface' => 'Uuid\Entity\Uuid'
    ],
    'router' => [
        'routes' => [
            'uuid' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => false,
                'options' => [
                    'route' => '/uuid'
                ],
                'child_routes' => [
                    'trash' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/trash/:id',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\UuidController',
                                'action' => 'trash'
                            ]
                        ]
                    ],
                    'recycle-bin' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/recycle-bin',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\UuidController',
                                'action' => 'recycleBin'
                            ]
                        ]
                    ],
                    'restore' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/restore/:id',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\UuidController',
                                'action' => 'restore'
                            ]
                        ]
                    ],
                    'purge' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/purge/:id',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\UuidController',
                                'action' => 'purge'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'di' => [
        'allowed_controllers' => [
            'Uuid\Controller\UuidController'
        ],
        'definition' => [
            'class' => [
                'Uuid\Controller\UuidController' => [
                    'setUuidManager' => [
                        'required' => true
                    ],
                    'setUserManager' => [
                        'required' => true
                    ],
                    'setLanguageManager' => [
                        'required' => true
                    ]
                ],
                'Uuid\Manager\UuidManager' => [
                    'setObjectManager' => [
                        'required' => 'true'
                    ],
                    'setClassResolver' => [
                        'required' => 'true'
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                'Uuid\Manager\UuidManagerInterface' => 'Uuid\Manager\UuidManager',
                'Uuid\Router\UuidRouterInterface' => 'Uuid\Router\UuidRouter'
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
    ]
];