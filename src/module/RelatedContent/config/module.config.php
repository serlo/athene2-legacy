<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace RelatedContent;

return [
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Manager\RelatedContentManager' => __NAMESPACE__ . '\Factory\RelatedContentManagerFactory'
        ]
    ],
    'di'              => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\RelatedContentController'
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Controller\RelatedContentController' => [
                    'setRelatedContentManager' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\RelatedContentManagerInterface' => __NAMESPACE__ . '\Manager\RelatedContentManager',
                'Zend\Mvc\Router\RouteInterface'                          => 'Router'
            ]
        ]
    ],
    'class_resolver'  => [
        __NAMESPACE__ . '\Entity\ContainerInterface' => __NAMESPACE__ . '\Entity\Container',
        __NAMESPACE__ . '\Entity\ExternalInterface'  => __NAMESPACE__ . '\Entity\External',
        __NAMESPACE__ . '\Entity\InternalInterface'  => __NAMESPACE__ . '\Entity\Internal',
        __NAMESPACE__ . '\Entity\CategoryInterface'  => __NAMESPACE__ . '\Entity\Category',
        __NAMESPACE__ . '\Entity\HolderInterface'    => __NAMESPACE__ . '\Entity\Holder'
    ],
    'doctrine'        => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ]
            ],
            'orm_default'             => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'router'          => [
        'routes' => [
            'related-content' => [
                'type'         => 'Zend\Mvc\Router\Http\Segment',
                'options'      => [
                    'route'    => '/{related-content}',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\RelatedContentController'
                    ]
                ],
                'child_routes' => [
                    'manage'       => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/:id',
                            'defaults' => [
                                'action' => 'manage'
                            ]
                        ]
                    ],
                    'add-internal' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/add-internal/:id',
                            'defaults' => [
                                'action' => 'addInternal'
                            ]
                        ]
                    ],
                    'add-category' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/add-category/:id',
                            'defaults' => [
                                'action' => 'addCategory'
                            ]
                        ]
                    ],
                    'add-external' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/add-external/:id',
                            'defaults' => [
                                'action' => 'addExternal'
                            ]
                        ]
                    ],
                    'remove'       => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/remove-internal/:id',
                            'defaults' => [
                                'action' => 'remove'
                            ]
                        ]
                    ],
                    'order'        => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/order',
                            'defaults' => [
                                'action' => 'order'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'view_helpers'    => [
        'factories' => [
            'related' => __NAMESPACE__ . '\Factory\RelatedContentHelperFactory'
        ]
    ]
];
