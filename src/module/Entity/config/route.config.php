<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author         Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license        LGPL-3.0
 * @license        http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link           https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright      Copyright (c) 2013 Gesellschaft f√ºr freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity;

return [
    'router' => [
        'routes' => [
            'entity' => [
                'type'         => 'Zend\Mvc\Router\Http\Segment',
                'options'      => [
                    'route' => '/entity'
                ],
                'child_routes' => [
                    'create'     => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/create/:type',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action'     => 'create'
                            ]
                        ]
                    ],
                    'trash'      => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/trash/:entity',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action'     => 'trash'
                            ]
                        ]
                    ],
                    'restore'    => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/restore/:entity',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action'     => 'restore'
                            ]
                        ]
                    ],
                    'purge'      => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/purge/:entity',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action'     => 'purge'
                            ]
                        ]
                    ],
                    'repository' => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route'    => '/repository',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\RepositoryController'
                            ]
                        ],
                        'child_routes' => [
                            'checkout'     => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/checkout/:entity/:revision',
                                    'defaults' => [
                                        'action' => 'checkout'
                                    ]
                                ]
                            ],
                            'reject'       => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/reject/:entity/:revision',
                                    'defaults' => [
                                        'action' => 'reject'
                                    ]
                                ]
                            ],
                            'compare'      => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/compare/:entity/:revision',
                                    'defaults' => [
                                        'action' => 'compare'
                                    ]
                                ]
                            ],
                            'history'      => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/history/:entity',
                                    'defaults' => [
                                        'action' => 'history'
                                    ]
                                ]
                            ],
                            'add-revision' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/add-revision/:entity',
                                    'defaults' => [
                                        'action' => 'addRevision'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'license'    => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route'    => '/license',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\LicenseController'
                            ]
                        ],
                        'child_routes' => [
                            'update' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/update/:entity',
                                    'defaults' => [
                                        'action' => 'update'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'link'       => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route'    => '/link',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\LinkController'
                            ]
                        ],
                        'child_routes' => [
                            'order' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/order/:type/:entity',
                                    'defaults' => [
                                        'action' => 'orderChildren'
                                    ]
                                ]
                            ],
                            'move'  => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/move/:type/:entity/:from',
                                    'defaults' => [
                                        'action' => 'move'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'page'       => [
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'options'       => [
                            'route'    => '/view/:entity',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\PageController',
                                'action'     => 'index'
                            ]
                        ]
                    ],
                    'taxonomy'   => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route' => '/taxonomy'
                        ],
                        'child_routes' => [
                            'update' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/update/:entity',
                                    'defaults' => [
                                        'controller' => __NAMESPACE__ . '\Controller\TaxonomyController',
                                        'action'     => 'update'
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