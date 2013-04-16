<?php
namespace Object;

return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'Entity\EntityManager' => array(
                ),
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService',
                'Entity\Service\EntityServiceInterface' => 'Entity\Service\EntityService',
            ),
            'Entity\SharedEntityManager' => array(
                'shared' => false,
            ),
            'Entity\Service\EntityService' => array(
                'shared' => false,
            ),
            'alias' => array(
                'Entity\SharedEntityManager' => __NAMESPACE__.'\EntityManager',
            )
        )
    ),
);