<?php

use DoctrineORMModule\Service\EntityManagerFactory;
use DoctrineORMModule\Service\DBALConnectionFactory;

$dbParams = array(
    'host' => 'localhost',
    'port' => '3306',
    'user' => 'travis',
    'password' => '',
    'database' => 'serlo_test'
);

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_test' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => $dbParams['host'],
                    'port' => $dbParams['port'],
                    'user' => $dbParams['user'],
                    'password' => $dbParams['password'],
                    'dbname' => $dbParams['database']
                )
            )
        ),
        // now
        // you
        // define
        // the
        // entity
        // manager
        // configuration
        'entitymanager' => array(
            // This
            // is
            // the
            // alternative
            // config
            'orm_test' => array(
                'connection' => 'orm_test',
                'configuration' => 'orm_default'
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'doctrine.entitymanager.orm_test' => new EntityManagerFactory('orm_test'),
            'doctrine.connection.orm_test' => new DBALConnectionFactory('orm_test')
        )
    )
);