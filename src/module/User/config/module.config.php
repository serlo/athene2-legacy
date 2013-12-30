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
use User\View\Helper\Rbac;
use User\View\Helper\Guard;
use User\Entity\UserInterface;
use User\Authentication\HashFilter;
use User\Authentication\Storage\UserRepository;
use User\Authentication\AuthenticationService;

/**
 * @codeCoverageIgnore
 */
return array(
    'uuid_router' => array(
        'routes' => array(
            'user' => '/user/profile/%d'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Authentication\AuthenticationService' => function ($sm)
            {
                $instance = new AuthenticationService();
                $instance->setAdapter($sm->get('User\Authentication\Adapter\UserAuthAdapter'));
                $instance->setStorage($sm->get('User\Authentication\Storage\UserSessionStorage'));
                return $instance;
            },
            __NAMESPACE__ . '\Form\Register' => function ($sm)
            {
                $form = new Form\Register($sm->get('Doctrine\ORM\EntityManager'));
                return $form;
            },
            __NAMESPACE__ . '\Authentication\Storage\UserRepository' => __NAMESPACE__ . '\Authentication\Storage\StorageFactory'
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
            },
            'guard' => function ($sm)
            {
                $helper = new Guard();
                $helper->setGuardPluginManager($sm->getServiceLocator()
                    ->get('ZfcRbac\Guard\GuardPluginManager'));
                $helper->setApplication($sm->getServiceLocator()
                    ->get('Application'));
                $helper->setRouter($sm->getServiceLocator()
                    ->get('router'));
                return $helper;
            }
        )
    ),
    'class_resolver' => array(
        'User\Entity\UserInterface' => 'User\Entity\User',
        'User\Entity\RoleInterface' => 'User\Entity\Role',
        'User\Notification\Entity\NotificationEventInterface' => 'User\Entity\NotificationEvent',
        'User\Notification\Service\NotificationServiceInterface' => 'User\Notification\Service\NotificationService',
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
                    ),
                    'setHydrator' => array(
                        'required' => true
                    )
                ),
                'User\Notification\Service\NotificationService' => array(
                    'setEventManager' => array(
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
                __NAMESPACE__ . '\Notification\Listener\RepositoryManagerListener' => array(
                    'setNotificationLogManager' => array(
                        'required' => true
                    ),
                    'setSubscriptionManager' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
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
                __NAMESPACE__ . '\Authentication\Storage\UserSessionStorage' => array(
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setClassResolver' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Authentication\Adapter\UserAuthAdapter' => array(
                    'setHashService' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
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
            'User\Notification\Service\NotificationService' => array(
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
                                'action' => 'roles'
                            )
                        )
                    ),
                    'role' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/role/:role',
                            'defaults' => array(
                                'action' => 'role'
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
                        'action' => 'profile'
                    )
                ),
                'child_routes' => array(
                    'role' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/:user/role'
                        ),
                        'child_routes' => array(
                            'add' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/add/:role',
                                    'defaults' => array(
                                        'action' => 'addRole'
                                    )
                                )
                            ),
                            'remove' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/remove/:role',
                                    'defaults' => array(
                                        'action' => 'removeRole'
                                    )
                                )
                            )
                        )
                    ),
                    'me' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/me',
                            'defaults' => array(
                                'action' => 'me'
                            )
                        )
                    ),
                    'password' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/password'
                        ),
                        'child_routes' => array(
                            'restore' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/restore[/:token]',
                                    'defaults' => array(
                                        'action' => 'restorePassword'
                                    )
                                )
                            ),
                            'change' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/change',
                                    'defaults' => array(
                                        'action' => 'changePassword'
                                    )
                                )
                            )
                        )
                    ),
                    'profile' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/profile/:id',
                            'defaults' => array(
                                'action' => 'profile'
                            )
                        )
                    ),
                    'login' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'action' => 'login'
                            )
                        )
                    ),
                    'dashboard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/dashboard',
                            'defaults' => array(
                                'action' => 'dashboard'
                            )
                        )
                    ),
                    'register' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
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
                                'action' => 'activate'
                            )
                        )
                    ),
                    'settings' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/settings',
                            'defaults' => array(
                                'action' => 'settings'
                            )
                        )
                    ),
                    'remove' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/remove/:id',
                            'defaults' => array(
                                'action' => 'remove'
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
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'User\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'storage' => 'User\Authentication\Storage\UserRepository',
                'credential_callable' => function (UserInterface $user, $passwordGiven)
                {
                    $filter = new HashFilter();
                    $salt = $filter->findSalt($user->getPassword());
                    $passwordGiven = $filter->hashPassword($passwordGiven, $salt);
                    
                    return $user->getPassword() === $passwordGiven && $user->hasRole('login') && ! $user->isTrashed();
                }
            )
        )
    )
);
