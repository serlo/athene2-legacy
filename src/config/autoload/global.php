<?php
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Global Configuration Override
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 *      control, so do not include passwords or other sensitive information in this
 *      file.
 */
return [
    'page_header_helper' => [
        'brand'     => 'www.serlo.org',
        'delimiter' => ' - '
    ],
    'brand'              => [
        'name' => 'Serlo <sup><small>beta</small></sup>'
    ],
    'doctrine'           => [
        'entitymanager' => [
            'orm_default' => [
                'connection'      => 'orm_default',
                'configuration'   => 'orm_default',
            ]
        ]
    ],
    'session'            => [
        'config'     => [
            'class'   => 'Zend\Session\Config\SessionConfig',
            'options' => [
                'name'                => 'athene2',
                'remember_me_seconds' => 6000,
                'use_cookies'         => true,
                'cookie_secure'       => false
            ]
        ],
        'storage'    => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => [
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent'
        ]
    ],
    'service_manager'    => [
        'factories' => [
            'Zend\Mail\Transport\SmtpOptions' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('config')['smtp_options'];

                    return new \Zend\Mail\Transport\SmtpOptions($config);
                },
            'doctrine.cache.apccache'      => function ($sm) {
                    $cache    = new \Doctrine\Common\Cache\ApcCache();
                    return $cache;
                },
        ]
    ],
    'smtp_options'       => [
        'name'              => 'localhost.localdomain',
        'host'              => 'localhost',
        'connection_class'  => 'login',
        'connection_config' => [
            'username' => 'postmaster',
            'password' => ''
        ]
    ],
    'di'                 => [
        'instance' => [
            'preferences' => [
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Doctrine\Common\Persistence\ObjectManager'   => 'Doctrine\ORM\EntityManager'
            ]
        ]
    ],
    'sphinx'             => [
        'host' => '127.0.0.1',
        'port' => 9306
    ],
    'zendDiCompiler'     => [],
    'zfc_rbac'           => [
        'redirect_strategy' => [
            'redirect_to_route_connected'    => 'authorization/forbidden',
            'redirect_to_route_disconnected' => 'authentication/login',
            'append_previous_uri'            => true,
            'previous_uri_query_key'         => 'redir'
        ]
    ]
];
