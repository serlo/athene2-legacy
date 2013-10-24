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
use User\View\Helper\Notification;

/**
 * @codeCoverageIgnore
 */
return array(
    'uuid_router' => array(
        'routes' => array(
            'user' => '/user/show/%d'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            /*'User\Service\UserLogService' => function ($sm)
            {
                $srv = new Service\UserLogService();
                $srv->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
                $srv->setAuthService($sm->get('Zend\Authentication\AuthenticationService'));
                return $srv;
            },*/
            __NAMESPACE__ . '\Service\UserService' => function ($sm)
            {
                $srv = new Service\UserService();
                $srv->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                return $srv;
            },
            'Zend\Authentication\AuthenticationService' => function ($sm)
            {
                return new \Zend\Authentication\AuthenticationService();
            },
            __NAMESPACE__ . '\Form\Register' => function ($sm)
            {
                $form = new Form\Register($sm->get('Doctrine\ORM\EntityManager'));
                return $form;
            },
            __NAMESPACE__ . '\Manager\UserManager' => function ($sm)
            {
                $instance = new Manager\UserManager();
                $instance->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                $instance->setAuthenticationService($sm->get('Zend\Authentication\AuthenticationService'));
                $instance->setServiceLocator($sm);
                $instance->setObjectManager($sm->get('EntityManager'));
                $instance->setUuidManager($sm->get('Uuid\Manager\UuidManager'));
                return $instance;
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
            'notifications' => function ($sm)
            {
                $helper = new Notification();
                $helper->setUserManager($sm->getServiceLocator()
                    ->get('User\Manager\UserManager'));
                $helper->setNotificationManager($sm->getServiceLocator()
                    ->get('User\Notification\NotificationManager'));
                return $helper;
            },
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
        'User\Service\UserServiceInterface' => 'User\Service\UserService',
        'User\Notification\Entity\NotificationEventInterface' => 'User\Entity\NotificationEvent',
        'User\Notification\Service\NotificationServiceInterface' => 'User\Notification\Service\NotificationInterface',
        'User\Notification\Entity\NotificationInterface' => 'User\Entity\Notification',
        'User\Notification\Entity\SubscriptionInterface' => 'User\Entity\Subscription',
        'User\Notification\Entity\NotificationLogInterface' => 'User\Entity\NotificationLog'
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\UsersController',
            __NAMESPACE__ . '\Controller\UserController',
            __NAMESPACE__ . '\Notification\Controller\WorkerController',
            __NAMESPACE__ . '\Controller\RoleController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Notification\Listener\EntityControllerListener' => array(
                    'setNotificationLogManager' => array(
                        'required' => true
                    ),
                    'setUuidManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Notification\Listener\DiscussionControllerListener' => array(
                    'setNotificationLogManager' => array(
                        'required' => true
                    ),
                    'setSubscriptionManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Notification\Listener\RepositoryPluginControllerListener' => array(
                    'setNotificationLogManager' => array(
                        'required' => true
                    ),
                    'setSubscriptionManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Notification\SubscriptionManager' => array(
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Notification\NotificationManager' => array(
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Notification\NotificationLogManager' => array(
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setEventManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Notification\NotificationWorker' => array(
                    'setUserManager' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setSubscriptionManager' => array(
                        'required' => true
                    ),
                    'setNotificationManager' => array(
                        'required' => true
                    ),
                    'setClassResolver' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Notification\Controller\WorkerController' => array(
                    'setNotificationWorker' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Authentication\Adapter\UserAuthAdapter' => array(
                    'setHashService' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Authentication\HashService' => array(),
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
                    ),
                    'setRegisterForm' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
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
                __NAMESPACE__ . '\Authentication\Adapter\AdapterInterface' => __NAMESPACE__ . '\Authentication\Adapter\UserAuthAdapter',
                __NAMESPACE__ . '\Notification\SubscriptionManagerInterface' => __NAMESPACE__ . '\Notification\SubscriptionManager',
                __NAMESPACE__ . '\Notification\NotificationManagerInterface' => __NAMESPACE__ . '\Notification\NotificationManager',
                __NAMESPACE__ . '\Notification\NotificationLogManagerInterface' => __NAMESPACE__ . '\Notification\NotificationLogManager'
            ),
            'User\Service\UserService' => array(
                'shared' => false
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'notification' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/notification'
                ),
                'child_routes' => array(
                    'worker' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/worker',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Notification\Controller\WorkerController',
                                'action' => 'run'
                            )
                        )
                    )
                )
            ),
            'user' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => false,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\UserController',
                        'action' => 'index'
                    )
                ),
                'child_routes' => array(
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
                    'activate' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/activate/:token',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\UserController',
                                'action' => 'activate'
                            )
                        )
                    )
                )
            ),
            'restricted' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/restricted'
                ),
                'child_routes' => array(
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
    ),
    'navigation' => array(
        'default' => array(
            'restricted' => array(
                'pages' => array(
                    array(
                        'label' => 'Benutzer',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Benutzer verwalten',
                                'route' => 'restricted/users'
                            ),
                            array(
                                'label' => 'Benutzerrollen verwalten',
                                'route' => 'restricted/users/roles'
                            )
                        )
                    )
                )
            )
        )
    )
);

