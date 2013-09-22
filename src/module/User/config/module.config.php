<?php
/**
 *
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace User;

use User\View\Helper\Authenticator;
use Zend\Authentication\Storage\Session as Storage;

/**
 * @codeCoverageIgnore
 */
return array(
    'service_manager' => array(
        'factories' => array(
            /*'User\Service\UserLogService' => function ($sm)
            {
                $srv = new Service\UserLogService();
                $srv->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
                $srv->setAuthService($sm->get('Zend\Authentication\AuthenticationService'));
                return $srv;
            },*/
            'User\Service\UserService' => function ($sm)
            {
                $srv = new Service\UserService();
                $srv->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                return $srv;
            },
            'Zend\Authentication\AuthenticationService' => function ($sm)
            {
                return new \Zend\Authentication\AuthenticationService();
            }
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'authentication' => function ($sm)
            {
                $helper = new Authenticator();
                $helper->setAuthenticationService($sm->getServiceLocator()
                    ->get('Zend\Authentication\AuthenticationService'));
                return $helper;
            }
        )
    ),
    'class_resolver' => array(
        'User\Entity\UserInterface' => 'User\Entity\User',
        'User\Entity\RoleInterface' => 'User\Entity\Role',
        'User\Service\UserServiceInterface' => 'User\Service\UserService'
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\UsersController',
            __NAMESPACE__ . '\Controller\UserController',
            __NAMESPACE__ . '\Controller\RoleController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Authentication\Adapter\UserAuthAdapter' => array(
                    'setHashService' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Authentication\HashService' => array(),
                __NAMESPACE__ . '\Manager\UserManager' => array(
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setAuthenticationService' => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\UsersController' => array(
                    'setUserManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\UserController' => array(
                    'setUserManager' => array(
                        'required' => true
                    ),
                    'setAuthenticationService' => array(
                        'required' => true
                    ),
                    'setAuthAdapter' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\RoleController' => array(
                    'setUserManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\UserManagerInterface' => __NAMESPACE__ . '\Manager\UserManager',
                __NAMESPACE__ . '\Authentication\HashServiceInterface' => __NAMESPACE__ . '\Authentication\HashService',
                __NAMESPACE__ . '\Authentication\Adapter\AdapterInterface' => __NAMESPACE__ . '\Authentication\Adapter\UserAuthAdapter'
            ),
            'User\Service\UserService' => array(
                'shared' => false
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\UserController',
                        'action' => 'login'
                    )
                )
            ),
            'register' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/register',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\UserController',
                        'action' => 'register'
                    )
                )
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\UserController',
                        'action' => 'logout'
                    )
                )
            ),
            'user' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/user/:user',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\UserController',
                        'action' => 'index'
                    )
                ),
                'child_routes' => array(
                    'role' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/role/:action[/:role]',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\RoleController',
                                'action' => 'index'
                            )
                        )
                    ),
                    'update' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/update/:user',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\UserController',
                                'action' => 'update'
                            )
                        )
                    )
                )
            ),
            'users' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/users',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\UsersController',
                        'action' => 'users'
                    )
                ),
                'child_routes' => array(
                    'roles' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/roles',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\UsersController',
                                'action' => 'roles'
                            )
                        )
                    ),
                    'role' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/role/:role',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\UsersController',
                                'action' => 'role'
                            )
                        )
                    )
                )
            )
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);