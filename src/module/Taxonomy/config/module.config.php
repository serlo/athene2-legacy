<?php
namespace Taxonomy;

return array(
    'router' => array(
        'routes' => array(
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Taxonomy\Controller\TermController'
        ),
        'definition' => array(
            'class' => array(
                'Taxonomy\Controller\TermController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    ),
                ),
                'Taxonomy\TermManager' => array(
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
                'Taxonomy\Service\TermService' => array(
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setTermManager' => array(
                        'required' => 'true'
                    ),
                ),
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Taxonomy\SharedTaxonomyManagerInterface' => 'Taxonomy\SharedTaxonomyManager',
                'Term\Manager\TermManagerInterface' => 'Term\Manager\TermManager'
                //'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService',
                //'Entity\Service\EntityServiceInterface' => 'EventManager',
                //'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager',
            	//'SharedTaxonomyManagerInterface' => 'SharedTaxonomyManager'
            ),
            'Taxonomy\TermManager' => array(
                'shared' => false
            ),
            'Taxonomy\Service\TermService' => array(
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