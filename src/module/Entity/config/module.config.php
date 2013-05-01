<?php
namespace Entity;

use Versioning;
return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'Entity\EntityManager' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceManager' => array(
                        'required' => 'true'
                    )
                ),
                'Entity\Service\EntityService' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setRepositoryManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageService' => array(
                        'required' => 'true'
                    ),
                    'setAuthService' => array(
                        'required' => 'true'
                    ),
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    ),
                    /*'setLanguageManager' => array(
                        'required' => 'true'
                    ),*/
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService',
                'Entity\Service\EntityServiceInterface' => 'Entity\Service\EntityService',
                //'Core\Service\LanguageManagerInterface' => 'Core\Service\LanguageManager',
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Entity\Service\EntityServiceInterface' => 'EventManager',
                'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager',
            	'Taxonomy\SharedTaxonomyManagerInterface' => 'Taxonomy\SharedTaxonomyManager'
            ),
            'Entity\Service\EntityService' => array(
                'shared' => false
            )
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);