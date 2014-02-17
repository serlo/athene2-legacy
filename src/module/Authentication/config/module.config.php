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
namespace Authentication;

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Authentication\AuthenticationService'   => __NAMESPACE__ . '\Factory\AuthenticationServiceFactory',
            __NAMESPACE__ . '\Storage\UserSessionStorage' => __NAMESPACE__ . '\Factory\UserSessionStorageFactory',
            __NAMESPACE__ . '\HashService'                => __NAMESPACE__ . '\Factory\HashServiceFactory'

        )
    ),
    'controllers'     => [
        'factories' => [
            __NAMESPACE__ . '\Controller\AuthenticationController' => __NAMESPACE__ . '\Factory\AuthenticationControllerFactory'
        ]
    ],
    'di'              => array(
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\HashServiceInterface'     => __NAMESPACE__ . '\HashService',
                __NAMESPACE__ . '\Adapter\AdapterInterface' => __NAMESPACE__ . '\Adapter\UserAuthAdapter',
            )
        )
    ),
    'router'          => array(
        'routes' => array(
            'authentication' => array(
                'type'         => 'Zend\Mvc\Router\Http\Segment',
                'options'      => array(
                    'route'    => '/auth',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\AuthenticationController',
                    )
                ),
                'child_routes' => array(
                    'login'    => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/login',
                            'defaults' => array(
                                'action' => 'login'
                            )
                        )
                    ),
                    'logout'   => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/logout',
                            'defaults' => array(
                                'action' => 'logout'
                            )
                        )
                    ),
                    'activate' => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/activate[/:token]',
                            'defaults' => array(
                                'action' => 'activate'
                            )
                        )
                    ),
                    'password' => array(
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => array(
                            'route' => '/password'
                        ),
                        'child_routes' => array(
                            'change' => array(
                                'type'          => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options'       => array(
                                    'route'    => '/change',
                                    'defaults' => array(
                                        'action' => 'changePassword'
                                    )
                                )
                            ),
                            'restore' => array(
                                'type'          => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options'       => array(
                                    'route'    => '/restore[/:token]',
                                    'defaults' => array(
                                        'action' => 'restorePassword'
                                    )
                                )
                            )
                        )
                    ),
                )
            )
        )
    )
);
