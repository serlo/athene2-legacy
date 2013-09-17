<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
    'doctrine' => array(
        'entitymanager' => array(
            'orm_default' => array(
                'connection' => 'orm_default',
                'configuration' => 'orm_default'
            )
        )
    ),
    'service_manager' => array(
        'aliases' => array(
            'EntityManager' => 'Doctrine\ORM\EntityManager'
        )
    ),
    'dbParams' => array(
        'host' => '',
        'port' => '',
        'user' => '',
        'password' => '',
        'database' => ''
    ),
    'smtpParams' => array(
        'name' => 'smtp.serlo.org',
        'host' => 'smtp.serlo.org',
        'connection_class' => 'login',
        'connection_config' => array(
            'username' => 'aeneas.rekkas@serlo.org',
            'password' => 'v4uf428g'
        )
    ),
    'session' => array(
        'use_cookies' => true,
        'cookie_secure' => false,
        'remember_me_seconds' => 1800,
    ),
    'di' => array(
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Doctrine\Common\Persistence\ObjectManager' => 'Doctrine\ORM\EntityManager'
            )
        )
    )
);