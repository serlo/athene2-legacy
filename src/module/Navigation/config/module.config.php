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
namespace Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'navigation'         => [
        'providers' => [
            'Navigation\Provider\ContainerRepositoryProvider'
        ]
    ],
    'doctrine'           => [
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
    'zfc_rbac'           => [
        'assertion_map' => [
            'navigation.manage' => 'Authorization\Assertion\InstanceAssertion',
        ],
    ],
    'navigation_helpers' => [
        'factories' => [
            'menu' => __NAMESPACE__ . '\Factory\NavigationMenuHelperFactory',
        ]
    ],
    'service_manager'    => [
        'factories' => [
            'Zend\View\Helper\Navigation\PluginManager'             => __NAMESPACE__ . '\Factory\NavigationHelperPluginManagerFactory',
            __NAMESPACE__ . '\View\Helper\Menu'                     => __NAMESPACE__ . '\Factory\NavigationMenuHelperFactory',
            __NAMESPACE__ . '\Storage\Storage'                      => __NAMESPACE__ . '\Factory\NavigationStorageFactory',
            __NAMESPACE__ . '\Storage\NavigationHelperStorage'      => __NAMESPACE__ . '\Factory\NavigationHelperStorageFactory',
            __NAMESPACE__ . '\Manager\NavigationManager'            => __NAMESPACE__ . '\Factory\NavigationManagerFactory',
            __NAMESPACE__ . '\Provider\ContainerRepositoryProvider' => __NAMESPACE__ . '\Factory\ContainerRepositoryProviderFactory',
            __NAMESPACE__ . '\Form\ContainerForm'                   => __NAMESPACE__ . '\Factory\ContainerFormFactory',
            __NAMESPACE__ . '\Form\PageForm'                        => __NAMESPACE__ . '\Factory\PageFormFactory',
            __NAMESPACE__ . '\Form\ParameterForm'                   => __NAMESPACE__ . '\Factory\ParameterFormFactory',
            __NAMESPACE__ . '\Form\ParameterKeyForm'                => __NAMESPACE__ . '\Factory\ParameterKeyFormFactory',
            __NAMESPACE__ . '\Form\PositionPageForm'                => __NAMESPACE__ . '\Factory\PositionPageFormFactory',
            'top_left_navigation'                                   => __NAMESPACE__ . '\Factory\TopLeftNavigationFactory',
            'top_right_navigation'                                  => __NAMESPACE__ . '\Factory\TopRightNavigationFactory',
            'top_center_navigation'                                 => __NAMESPACE__ . '\Factory\TopCenterNavigationFactory',
            'footer_navigation'                                     => __NAMESPACE__ . '\Factory\FooterNavigationFactory',
            'subject_navigation'                                    => __NAMESPACE__ . '\Factory\SubjectNavigationFactory',
            'navigation'                                            => function (ServiceLocatorInterface $sm) {
                    // This is neccessary because the ServiceManager would create multiple instances of the factory and thus injecting the RouteMatch wouldn't work
                    return $sm->get(__NAMESPACE__ . '\Factory\DefaultNavigationFactory')->createService($sm);
                },
        ]
    ],
    'view_helpers'       => [
        'factories' => [
            'navigation' => __NAMESPACE__ . '\Factory\NavigationHelperFactory'
        ]
    ],
    'controllers'        => [
        'factories' => [
            __NAMESPACE__ . '\Controller\NavigationController' => __NAMESPACE__ . '\Factory\NavigationControllerFactory'
        ]
    ],
    'di'                 => [
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\NavigationManagerInterface' => __NAMESPACE__ . '\Manager\NavigationManager'
            ]
        ]
    ],
    'class_resolver'     => [
        __NAMESPACE__ . '\Entity\ContainerInterface'    => __NAMESPACE__ . '\Entity\Container',
        __NAMESPACE__ . '\Entity\PageInterface'         => __NAMESPACE__ . '\Entity\Page',
        __NAMESPACE__ . '\Entity\ParameterInterface'    => __NAMESPACE__ . '\Entity\Parameter',
        __NAMESPACE__ . '\Entity\ParameterKeyInterface' => __NAMESPACE__ . '\Entity\ParameterKey'
    ],
    'router'             => [
        'routes' => [
            'navigation' => [
                'type'         => 'Zend\Mvc\Router\Http\Segment',
                'options'      => [
                    'route'    => '/navigation',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\NavigationController'
                    ]
                ],
                'child_routes' => [
                    'manage'    => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/manage',
                            'defaults' => [
                                'action' => 'index'
                            ]
                        ]
                    ],
                    'container' => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route' => '/container',
                        ],
                        'child_routes' => [
                            'get'    => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/get/:container',
                                    'defaults' => [
                                        'action' => 'getContainer'
                                    ]
                                ]
                            ],
                            'create' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/create',
                                    'defaults' => [
                                        'action' => 'createContainer'
                                    ]
                                ]
                            ],
                            'remove' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/remove/:container',
                                    'defaults' => [
                                        'action' => 'removeContainer'
                                    ]
                                ]
                            ],
                        ],
                    ],
                    'page'      => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route' => '/page',
                        ],
                        'child_routes' => [
                            'get'    => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/get/:container',
                                    'defaults' => [
                                        'action' => 'getPage'
                                    ]
                                ]
                            ],
                            'create' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/create/:container[/:parent]',
                                    'defaults' => [
                                        'action' => 'createPage'
                                    ]
                                ]
                            ],
                            'update' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/update/:page',
                                    'defaults' => [
                                        'action' => 'updatePage'
                                    ]
                                ]
                            ],
                            'remove' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/remove/:page',
                                    'defaults' => [
                                        'action' => 'removePage'
                                    ]
                                ]
                            ],
                        ],
                    ],
                    'parameter' => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route' => '/parameter',
                        ],
                        'child_routes' => [
                            'create' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/create/:page[/:parent]',
                                    'defaults' => [
                                        'action' => 'createParameter'
                                    ]
                                ]
                            ],
                            'remove' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/remove/:parameter',
                                    'defaults' => [
                                        'action' => 'removeParameter'
                                    ]
                                ]
                            ],
                            'update' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/update/:parameter',
                                    'defaults' => [
                                        'action' => 'updateParameter'
                                    ]
                                ]
                            ],
                            'key'    => [
                                'type'         => 'Zend\Mvc\Router\Http\Segment',
                                'options'      => [
                                    'route' => '/key',
                                ],
                                'child_routes' => [
                                    'create' => [
                                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => [
                                            'route'    => '/create',
                                            'defaults' => [
                                                'action' => 'createParameterKey'
                                            ]
                                        ]
                                    ],
                                ]
                            ]
                        ],
                    ],
                ]
            ]
        ]
    ]
];