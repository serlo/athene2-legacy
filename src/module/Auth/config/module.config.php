<?php
use Helloworld;
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
            )
        )
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'auth' => 'Auth\Controller\Plugin\Auth'
        )
    ),
    'controllers' => array(
        'factories' => array(
            'Auth\Controller\Auth' => 'Auth\Controller\AuthControllerFactory'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
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
            'Auth\Service\AuthService' => 'Auth\Service\AuthServiceFactory'
        ),
    )
);