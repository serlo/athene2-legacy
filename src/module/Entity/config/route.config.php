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
                'type'         => 'literal',
                'options'      => [
                    'route' => '/entity'
                ],
                'child_routes' => [
                    'api' => [
                        'type'         => 'literal',
                        'options'      => [
                            'route'    => '/api',
                        ],
                        'child_routes' => [
                            'json' => [
                                'type'         => 'literal',
                                'options'      => [
                                    'route'    => '/json',
                                    'defaults' => [
                                        'controller' => __NAMESPACE__ . '\Controller\JsonApiController'
                                    ]
                                ],
                                'child_routes' => [
                                    'export' => [
                                        'type'    => 'segment',
                                        'options' => [
                                            'route'    => '/export/:type',
                                            'defaults' => [
                                                'action' => 'export'
                                            ]
                                        ]
                                    ],
                                    'rss' => [
                                        'type'    => 'segment',
                                        'options' => [
                                            'route'    => '/rss/:type/:age',
                                            'defaults' => [
                                                'action' => 'rss'
                                            ]
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ],
                    'create'     => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/create/:type',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action'     => 'create'
                            ]
                        ]
                    ],
                    'trash'      => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/trash/:entity',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action'     => 'trash'
                            ]
                        ]
                    ],
                    'restore'    => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/restore/:entity',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action'     => 'restore'
                            ]
                        ]
                    ],
                    'purge'      => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/purge/:entity',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action'     => 'purge'
                            ]
                        ]
                    ],
                    'repository' => [
                        'type'         => 'literal',
                        'options'      => [
                            'route'    => '/repository',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\RepositoryController'
                            ]
                        ],
                        'child_routes' => [
                            'checkout'     => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/checkout/:entity/:revision',
                                    'defaults' => [
                                        'action' => 'checkout'
                                    ]
                                ]
                            ],
                            'reject'       => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/reject/:entity/:revision',
                                    'defaults' => [
                                        'action' => 'reject'
                                    ]
                                ]
                            ],
                            'compare'      => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/compare/:entity/:revision',
                                    'defaults' => [
                                        'action' => 'compare'
                                    ]
                                ]
                            ],
                            'history'      => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/history/:entity',
                                    'defaults' => [
                                        'action' => 'history'
                                    ]
                                ]
                            ],
                            'add-revision' => [
                                'type'    => 'segment',
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
                        'type'         => 'literal',
                        'options'      => [
                            'route'    => '/license',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\LicenseController'
                            ]
                        ],
                        'child_routes' => [
                            'update' => [
                                'type'    => 'segment',
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
                        'type'         => 'literal',
                        'options'      => [
                            'route'    => '/link',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\LinkController'
                            ]
                        ],
                        'child_routes' => [
                            'order' => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/order/:type/:entity',
                                    'defaults' => [
                                        'action' => 'orderChildren'
                                    ]
                                ]
                            ],
                            'move'  => [
                                'type'    => 'segment',
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
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/view/:entity',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\PageController',
                                'action'     => 'index'
                            ]
                        ]
                    ],
                    'taxonomy'   => [
                        'type'         => 'literal',
                        'options'      => [
                            'route' => '/taxonomy'
                        ],
                        'child_routes' => [
                            'update' => [
                                'type'    => 'segment',
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