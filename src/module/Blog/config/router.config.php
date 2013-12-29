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
namespace Blog;

return [
    'router' => [
        'routes' => [
            'blog' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/blog',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\BlogController',
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'view-all' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/view-all/:id',
                            'defaults' => [
                                'action' => 'viewAll'
                            ]
                        ]
                    ],
                    'view' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/view/:id',
                            'defaults' => [
                                'action' => 'view'
                            ]
                        ]
                    ],
                    'post' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/post'
                        ],
                        'child_routes' => [
                            'create' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route' => '/create/:id',
                                    'defaults' => [
                                        'action' => 'create'
                                    ]
                                ]
                            ],
                            'view' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route' => '/view/:post',
                                    'defaults' => [
                                        'action' => 'viewPost'
                                    ]
                                ]
                            ],
                            'update' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route' => '/update/:post',
                                    'defaults' => [
                                        'action' => 'update'
                                    ]
                                ]
                            ],
                            'trash' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route' => '/trash/:post',
                                    'defaults' => [
                                        'action' => 'trash'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];