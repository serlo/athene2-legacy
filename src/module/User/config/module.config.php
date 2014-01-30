<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace User;

/**
 * @codeCoverageIgnore
 */
return array(
    'zfc_rbac'        => [
        'assertion_map' => [
            //'user.create' => 'User\Assertion\HasNoIdentityAssertion'
        ]
    ],
    'service_manager' => array(
        'factories' => array(
            __NAMESPACE__ . '\Manager\UserManager' => __NAMESPACE__ . '\Factory\UserManagerFactory',
            __NAMESPACE__ . '\Form\Register'       => function ($sm) {
                    $form = new Form\Register($sm->get('Doctrine\ORM\EntityManager'));

                    return $form;
                },
        )
    ),
    'class_resolver'  => array(
        'User\Entity\UserInterface' => 'User\Entity\User',
        'User\Entity\RoleInterface' => 'User\Entity\Role'
    ),
    'di'              => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\UsersController',
            __NAMESPACE__ . '\Controller\UserController'
        ),
        'definition'          => array(
            'class' => array(
                __NAMESPACE__ . '\Controller\UsersController' => array(
                    'setUserManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Hydrator\UserHydrator'      => array(
                    'setUuidManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\UserController'  => array(
                    'setUserManager'           => array(
                        'required' => true
                    ),
                    'setAuthenticationService' => array(
                        'required' => true
                    ),
                    'setInstanceManager'       => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance'            => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\UserManagerInterface' => __NAMESPACE__ . '\Manager\UserManager'
            )
        )
    ),
    'router'          => array(
        'routes' => array(
            'users' => array(
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options'       => array(
                    'route'    => '/users',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\UsersController',
                        'action'     => 'users'
                    )
                )
            ),
            'user'  => array(
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => false,
                'options'       => array(
                    'route'    => '/user',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\UserController',
                        'action'     => 'profile'
                    )
                ),
                'child_routes'  => array(
                    'role'      => array(
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => array(
                            'route' => '/:user/role'
                        ),
                        'child_routes' => array(
                            'add'    => array(
                                'type'          => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options'       => array(
                                    'route'    => '/add/:role',
                                    'defaults' => array(
                                        'action' => 'addRole'
                                    )
                                )
                            ),
                            'remove' => array(
                                'type'          => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options'       => array(
                                    'route'    => '/remove/:role',
                                    'defaults' => array(
                                        'action' => 'removeRole'
                                    )
                                )
                            )
                        )
                    ),
                    'me'        => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/me',
                            'defaults' => array(
                                'action' => 'me'
                            )
                        )
                    ),
                    'password'  => array(
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => array(
                            'route' => '/password'
                        ),
                        'child_routes' => array(
                            'restore' => array(
                                'type'          => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options'       => array(
                                    'route'    => '/restore[/:token]',
                                    'defaults' => array(
                                        'action' => 'restorePassword'
                                    )
                                )
                            ),
                            'change'  => array(
                                'type'          => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options'       => array(
                                    'route'    => '/change',
                                    'defaults' => array(
                                        'action' => 'changePassword'
                                    )
                                )
                            )
                        )
                    ),
                    'profile'   => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/profile/:id',
                            'defaults' => array(
                                'action' => 'profile'
                            )
                        )
                    ),
                    'login'     => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/login',
                            'defaults' => array(
                                'action' => 'login'
                            )
                        )
                    ),
                    'dashboard' => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/dashboard',
                            'defaults' => array(
                                'action' => 'dashboard'
                            )
                        )
                    ),
                    'register'  => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/register',
                            'defaults' => array(
                                'action' => 'register'
                            )
                        )
                    ),
                    'logout'    => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/logout',
                            'defaults' => array(
                                'action' => 'logout'
                            )
                        )
                    ),
                    'activate'  => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/activate/:token',
                            'defaults' => array(
                                'action' => 'activate'
                            )
                        )
                    ),
                    'settings'  => array(
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/settings',
                            'defaults' => array(
                                'action' => 'settings'
                            )
                        )
                    ),
                    'remove'    => array(
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/remove/:id',
                            'defaults' => array(
                                'action' => 'remove'
                            )
                        )
                    )
                )
            )
        )
    ),
    'doctrine'        => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default'             => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
    )
);
