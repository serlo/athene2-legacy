<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author       Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license      LGPL-3.0
 * @license      http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link         https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright    Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Authorization;

return [
    'zfc_rbac'           => [
        'guard_manager'     => [
            'factories' => [
                __NAMESPACE__ . '\Guard\HydratableControllerGuard' => __NAMESPACE__ . '\Factory\HydratableControllerGuardFactory'
            ]
        ],
        'assertion_manager' => [
            'factories' => [
                'Authorization\Assertion\RoleAssertion'            => __NAMESPACE__ . '\Factory\RoleAssertionFactory',
                'Authorization\Assertion\RequestLanguageAssertion' => __NAMESPACE__ . '\Factory\RequestLanguageAssertionFactory',
            ]
        ],
        'assertion_map'     => [
            'authorization.role.identity.modify' => 'Authorization\Assertion\RoleAssertion'
        ]
    ],
    'service_manager'    => [
        'factories' => [
            'Authorization\Service\RoleService'       => __NAMESPACE__ . '\Factory\RoleServiceFactory',
            'Authorization\Service\PermissionService' => __NAMESPACE__ . '\Factory\PermissionServiceFactory',
        ]
    ],
    'controller_plugins' => [
        'invokables' => [
            'assertGranted' => 'Authorization\Controller\Plugin\AssertGranted'
        ]
    ],
    'controllers'        => [
        'factories' => [
            __NAMESPACE__ . '\Controller\RoleController' => __NAMESPACE__ . '\Factory\RoleControllerFactory'
        ]
    ],
    'class_resolver'     => [
        __NAMESPACE__ . '\Entity\RoleInterface'       => 'User\Entity\Role',
        __NAMESPACE__ . '\Entity\PermissionInterface' => 'User\Entity\Permission'
    ],
    'router'             => [
        'routes' => [
            'authorization' => [
                'type'         => 'Zend\Mvc\Router\Http\Segment',
                'options'      => [
                    'route' => '/authorization',
                ],
                'child_routes' => [
                    'roles' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/roles',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\RoleController',
                                'action'     => 'roles'
                            ]
                        ],
                    ],
                    'role'  => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route'    => '/role',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\RoleController'
                            ]
                        ],
                        'child_routes' => [
                            'show'       => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/show/:role',
                                    'defaults' => [
                                        'action' => 'show'
                                    ]
                                ]
                            ],
                            'all'        => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/all',
                                    'defaults' => [
                                        'action' => 'all'
                                    ]
                                ]
                            ],
                            'user'       => [
                                'type'         => 'Zend\Mvc\Router\Http\Segment',
                                'options'      => [
                                    'route' => '/user',
                                ],
                                'child_routes' => [
                                    'add'    => [
                                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => [
                                            'route'    => '/add/:role',
                                            'defaults' => [
                                                'action' => 'addUser'
                                            ]
                                        ]
                                    ],
                                    'remove' => [
                                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => [
                                            'route'    => '/remove/:role',
                                            'defaults' => [
                                                'action' => 'removeUser'
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'permission' => [
                                'type'         => 'Zend\Mvc\Router\Http\Segment',
                                'options'      => [
                                    'route' => '/permission',
                                ],
                                'child_routes' => [
                                    'add'    => [
                                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => [
                                            'route'    => '/add/:role',
                                            'defaults' => [
                                                'action' => 'addPermission'
                                            ]
                                        ]
                                    ],
                                    'remove' => [
                                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => [
                                            'route'    => '/remove/:role/:permission',
                                            'defaults' => [
                                                'action' => 'removePermission'
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                        ]
                    ]
                ]
            ],
        ]
    ]
];
