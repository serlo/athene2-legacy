<?php

return array(
    'doctrine' => array(
        'connection'    => array(
            'orm_test' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => array(
                    'host'     => 'none',
                    'port'     => '',
                    'user'     => '',
                    'password' => '',
                    'dbname'   => ''
                )
            )
        ),
        'entitymanager' => array(
            'orm_default' => array(
                'connection'    => 'orm_test',
                'configuration' => 'orm_default'
            )
        )
    ),
);