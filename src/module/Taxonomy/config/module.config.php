<?php
namespace Taxonomy;

return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'Taxonomy\TaxonomyManager' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                ),
                'Taxonomy\SharedTaxonomyManager' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageService' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                ),
                'Taxonomy\Taxonomy\TermService' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                ),
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                //'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService',
                //'Entity\Service\EntityServiceInterface' => 'EventManager',
                //'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager',
            	//'SharedTaxonomyManagerInterface' => 'SharedTaxonomyManager'
            ),
            'Taxonomy\Taxonomy\TermService' => array(
                'shared' => false
            ),
            'Taxonomy\TaxonomyManager' => array(
                'shared' => false
            ),
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            //'Core\Service\LanguageService' => 'Core\Service\LanguageService',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
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