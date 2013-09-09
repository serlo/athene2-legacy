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
    'service_manager' => array(
        'aliases' => array(
            'EntityManager' => 'doctrine.entitymanager.orm_default',
            'Doctrine\ORM\EntityManager' => 'doctrine.entitymanager.orm_default',
            'Doctrine\Common\Persistence\ObjectManager' => 'doctrine.entitymanager.orm_default'
        )
    ),
    'di' => array(
        'aliases' => array(
            'Doctrine\Common\Persistence\ObjectManager' => 'doctrine.entitymanager.orm_default'
        )
    )
);