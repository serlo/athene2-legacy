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

use Authorization\Assertion\RequestLanguageAssertion;
use Authorization\Assertion\RoleAssertion;

return [
    'zendDiCompiler' => array(
        'scanDirectories' => array(
            __DIR__ . '/../src'
        ),
    ),
    'zfc_rbac'           => [
        'guard_manager'     => [
            'factories' => [
                __NAMESPACE__ . '\Guard\HydratableControllerGuard' => __NAMESPACE__ . '\Factory\HydratableControllerGuardFactory',
                __NAMESPACE__ . '\Guard\AssertiveControllerGuard'  => __NAMESPACE__ . '\Factory\AssertiveControllerGuardFactory'
            ]
        ],
        'assertion_manager' => [
            'factories' => [
                'Authorization\Assertion\RoleAssertion'            => function ($pluginManager) {
                        $instance = new RoleAssertion();
                        $instance->setLanguageManager(
                            $pluginManager->getServiceLocator()->get('Language\Manager\LanguageManager')
                        );
                        $instance->setPermissionService(
                            $pluginManager->getServiceLocator()->get(__NAMESPACE__ . '\Service\PermissionService')
                        );

                        return $instance;
                    },
                'Authorization\Assertion\RequestLanguageAssertion' => function ($pluginManager) {
                        $instance = new RequestLanguageAssertion();
                        $instance->setLanguageManager(
                            $pluginManager->getServiceLocator()->get('Language\Manager\LanguageManager')
                        );

                        return $instance;
                    }
            ]
        ],
        'assertion_map'     => [
            'authorization.role.identity.modify' => 'Authorization\Assertion\RoleAssertion'
        ]
    ],
    'controller_plugins' => [
        'invokables' => [
            'assertGranted' => 'Authorization\Controller\Plugin\AssertGranted'
        ]
    ],
    'class_resolver'     => [
        __NAMESPACE__ . '\Entity\RoleInterface'       => 'User\Entity\Role',
        __NAMESPACE__ . '\Entity\PermissionInterface' => 'User\Entity\Permission'
    ],
    'di'                 => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\RoleController'
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Controller\RoleController'          => [
                    'setRoleService'       => [
                        'required' => true
                    ],
                    'setUserManager'       => [
                        'required' => true
                    ],
                    'setPermissionService' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Assertion\RequestLanguageAssertion' => [
                    'setLanguageManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Service\RoleService'                => [
                    'setObjectManager'        => [
                        'required' => true
                    ],
                    'setUserManager'          => [
                        'required' => true
                    ],
                    'setClassResolver'        => [
                        'required' => true
                    ],
                    'setPermissionService'    => [
                        'required' => true
                    ],
                    'setAuthorizationService' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Service\PermissionService'          => [
                    'setObjectManager' => [
                        'required' => true
                    ],
                    'setClassResolver' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\Service\RoleServiceInterface'       => __NAMESPACE__ . '\Service\RoleService',
                __NAMESPACE__ . '\Service\PermissionServiceInterface' => __NAMESPACE__ . '\Service\PermissionService'
            ]
        ]
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
