<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
return array(
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'login'
                    )
                )
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'logout'
                    )
                )
            ),
            'register' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/register',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'register'
                    )
                )
            )
        )
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'auth' => 'Auth\Controller\Plugin\Auth',
            'acl' => 'Auth\Controller\Plugin\Acl',
            'permissions' => 'Auth\Controller\Plugin\Permissions'
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'auth' => function  ($sm)
            {
                
                $helper = new \Auth\View\Helper\Auth();
                $helper->setAuthService($sm->getServiceLocator()
                    ->get('Auth\Service\AuthService'));
                return $helper;
            },
            'acl' => function  ($sm)
            {
                
                $helper = new \Auth\View\Helper\Auth();
                $helper->setAuthService($sm->getServiceLocator()
                    ->get('Auth\Service\AuthService'));
                return $helper;
            }
        )
    ),
    'filters' => array(
        'invokables' => array(
            'passwordfilter' => 'Auth\Filter\PasswordFilter'
        ),
        'factories' => array(
            'passwordfilter' => function  ($sm)
            {
                $class = new \Auth\Filter\PasswordFilter();
                die(get_class($sm->getServiceLocator()->get('Auth\Service\HashService')));
                $class->setHashService($sm->getServiceLocator()
                    ->get('Auth\Service\HashService'));
                return $class;
            }
        ),
        'aliases' => array()
    ),
    'controllers' => array(
        'factories' => array(
            'Auth\Controller\Auth' => 'Auth\Controller\AuthControllerFactory',
            'Auth\Controller\Register' => function  ($sm)
            {
                $ct = new \Auth\Controller\RegisterController();
                
                $ct->getEventManager()->attach('signUpComplete', array(
                    $sm->getServiceLocator()
                        ->get('User\Service\UserService'),
                    'createListener'
                ));
                
                return $ct;
            }
        ),
        'invokables' => array()
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                'ACL' => 'Zend\Permissions\Acl\Acl'
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => function  ($sm)
            {
                $config = $sm->get('Config');
                $dbParams = $config['dbParams'];
                return new Zend\Db\Adapter\Adapter(array(
                    'driver' => 'pdo',
                    'dsn' => 'mysql:dbname=' . $dbParams['database'] . ';host=' . $dbParams['host'],
                    'database' => $dbParams['database'],
                    'username' => $dbParams['user'],
                    'password' => $dbParams['password'],
                    'hostname' => $dbParams['host']
                ));
            },
            'Auth\Service\HashService' => 'Auth\Service\HashService',
            'Auth\Service\AuthService' => 'Auth\Service\AuthServiceFactory',
            'Auth\Form\SignUp' => function  ($sm)
            {
                $class = new Auth\Form\SignUp();
                $class->setObjectManager($sm->get('EntityManager'));
                return $class;
            }
        ),
        'invokables' => array(
            'Zend\Permissions\Acl\Acl' => 'Zend\Permissions\Acl\Acl'
        )
    ),
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Route' => array(
            ),
            'ZfcRbac\Firewall\Controller' => array(
            )
        ),
        'identity_provider' => 'standard_identity'
    )
);