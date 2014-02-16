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
namespace Taxonomy;

use Taxonomy\View\Helper\TaxonomyHelper;

return [
    'term_router'     => [
        'routes' => []
    ],
    'class_resolver'  => [
        __NAMESPACE__ . '\Entity\TaxonomyTypeInterface' => __NAMESPACE__ . '\Entity\TaxonomyType',
        __NAMESPACE__ . '\Entity\TaxonomyInterface'     => __NAMESPACE__ . '\Entity\Taxonomy',
        __NAMESPACE__ . '\Entity\TaxonomyTermInterface' => __NAMESPACE__ . '\Entity\TaxonomyTerm'
    ],
    'view_helpers'    => [
        'factories' => [
            'taxonomy' => function ($helperPluginManager) {
                    $serviceLocator  = $helperPluginManager->getServiceLocator();
                    $moduleOptions   = $serviceLocator->get('Taxonomy\Options\ModuleOptions');
                    $taxonomyManager = $serviceLocator->get('Taxonomy\Manager\TaxonomyManager');
                    $plugin          = new TaxonomyHelper($moduleOptions, $taxonomyManager);

                    return $plugin;
                }
        ]
    ],
    'taxonomy'        => [
        'types' => [
            'root' => [
                'allowed_parents' => [],
                'rootable'        => true
            ]
        ]
    ],
    'router'          => [
        'routes' => [
            'taxonomy' => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/taxonomy',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\404'
                    ]
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'taxonomy' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action/:id',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\TaxonomyController'
                            ]
                        ]
                    ],
                    'term'     => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/term',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\TermController'
                            ]
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'action'          => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route' => '/:action[/:id]'
                                ]
                            ],
                            'route'           => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/route/:id',
                                    'defaults' => [
                                        'controller' => __NAMESPACE__ . '\Controller\TermRouterController',
                                        'action'     => 'index'
                                    ]
                                ]
                            ],
                            'create'          => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/create/:taxonomy/:parent',
                                    'defaults' => [
                                        'action' => 'create'
                                    ]
                                ]
                            ],
                            'order'           => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/order/:term',
                                    'defaults' => [
                                        'action' => 'order'
                                    ]
                                ]
                            ],
                            'organize'        => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/organize/:id',
                                    'defaults' => [
                                        'action' => 'organize'
                                    ]
                                ]
                            ],
                            'organize-all'    => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/organize-all',
                                    'defaults' => [
                                        'action' => 'organize'
                                    ]
                                ]
                            ],
                            'sort-associated' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/sort/:association/:term',
                                    'defaults' => [
                                        'controller' => __NAMESPACE__ . '\Controller\TermController',
                                        'action'     => 'orderAssociated'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Options\ModuleOptions'   => __NAMESPACE__ . '\Factory\ModuleOptionsFactory',
            __NAMESPACE__ . '\Manager\TaxonomyManager' => __NAMESPACE__ . '\Factory\TaxonomyManagerFactory',
            __NAMESPACE__ . '\Provider\NavigationProvider' => __NAMESPACE__ . '\Factory\NavigationProviderFactory',
        ]
    ],
    'di'              => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\TermController'
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Listener\EntityManagerListener' => [
                    'setTaxonomyManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Form\TermForm'                  => [],
                __NAMESPACE__ . '\Controller\TermController'      => [
                    'setTaxonomyManager' => [
                        'required' => true
                    ],
                    'setInstanceManager' => [
                        'required' => true
                    ],
                    'setUserManager'     => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Hydrator\TaxonomyTermHydrator'  => []
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\TaxonomyManagerInterface' => __NAMESPACE__ . '\Manager\TaxonomyManager'
            ]
        ]
    ],
    'uuid'            => [
        'permissions' => [
            'Taxonomy\Entity\TaxonomyTerm' => [
                'trash'   => 'taxonomy.term.trash',
                'restore' => 'taxonomy.term.restore',
                'purge' => 'taxonomy.term.purge'
            ],
        ]
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
    ]
];