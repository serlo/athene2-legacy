<?php
use DoctrineORMModule\Service\EntityManagerFactory;
use DoctrineORMModule\Service\DBALConnectionFactory;
use Zend\ServiceManager\ServiceManager;

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
        'entitymanager' => array(
            'orm_default' => array(
                'connection' => 'orm_test',
                'configuration' => 'orm_default'
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'doctrine.connection.orm_test' => new DBALConnectionFactory('orm_test')
        ),
    )
);