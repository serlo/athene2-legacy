<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
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
            __NAMESPACE__ . '\Controller\RoleController',
        ),
        'definition' => array(
            'class' => array(
                'User\Manager\UserManager' => array(
                    'setClassResolver' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Controller\UsersController' => array(
                    'setUserManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                ),
                __NAMESPACE__ . '\Controller\UserController' => array(
                    'setUserManager' => array(
                        'required' => 'true'
                    ),
                ),
                __NAMESPACE__ . '\Controller\RoleController' => array(
                    'setUserManager' => array(
                        'required' => 'true'
                    ),
                ),
            ),
        ),
        'instance' => array(
            'preferences' => array(
                'User\Manager\UserManagerInterface' => 'User\Manager\UserManager'
            ),
            'User\Service\UserService' => array(
                'shared' => false
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'User\Service\UserLogService' => function  ($sm)
            {
                $srv = new Service\UserLogService();
                $srv->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
                $srv->setAuthService($sm->get('Auth\Service\AuthService'));
                return $srv;
            },
            'User\Service\UserService' => function  ($sm)
            {
                $srv = new Service\UserService();
                $srv->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                return $srv;
            }
        )
    ),
    'router' => array(
        'routes' => array(
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