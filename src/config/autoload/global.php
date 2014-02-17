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
return array(
    'page_header_helper' => array(
        'brand'     => 'www.serlo.org',
        'delimiter' => ' - '
    ),
    'brand'              => array(
        'name' => 'Serlo <sup><small>beta</small></sup>'
    ),
    'doctrine'           => array(
        'entitymanager' => array(
            'orm_default' => array(
                'connection'      => 'orm_default',
                'configuration'   => 'orm_default',
            )
        )
    ),
    'session'            => array(
        'config'     => array(
            'class'   => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name'                => 'athene2',
                'remember_me_seconds' => 6000,
                'use_cookies'         => true,
                'cookie_secure'       => false
            )
        ),
        'storage'    => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent'
        )
    ),
    'service_manager'    => array(
        'factories' => array(
            'Zend\Mail\Transport\SmtpOptions' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('config')['smtp_options'];

                    return new \Zend\Mail\Transport\SmtpOptions($config);
                },
            'doctrine.cache.apccache'      => function ($sm) {
                    $cache    = new \Doctrine\Common\Cache\ApcCache();
                    return $cache;
                },
        )
    ),
    'smtp_options'       => array(
        'name'              => 'localhost.localdomain',
        'host'              => 'localhost',
        'connection_class'  => 'login',
        'connection_config' => array(
            'username' => 'postmaster',
            'password' => ''
        )
    ),
    'di'                 => array(
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Doctrine\Common\Persistence\ObjectManager'   => 'Doctrine\ORM\EntityManager'
            )
        )
    ),
    'sphinx'             => array(
        'host' => '127.0.0.1',
        'port' => 9306
    ),
    'zendDiCompiler'     => array(),
    'zfc_rbac'           => [
        'redirect_strategy' => [
            'redirect_to_route_connected'    => 'authorization/forbidden',
            'redirect_to_route_disconnected' => 'authentication/login',
            'append_previous_uri'            => true,
            'previous_uri_query_key'         => 'redir'
        ]
    ]
);
