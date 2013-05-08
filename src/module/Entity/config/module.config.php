<?php
namespace Entity;

return array(
    'di' => array(
        'allowed_controllers' => array(
            'Entity\LearningObjects\Exercise\Controller\TextExerciseController',
            'Entity\LearningObjects\Solution\Controller\TextSolutionController',
        ),
        'definition' => array(
            'class' => array(
                'Entity\LearningObjects\Solution\Controller\TextSolutionController' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),                
                ),
                'Entity\LearningObjects\Exercise\Controller\TextExerciseController' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),                
                ),
                'Entity\EntityManager' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceManager' => array(
                        'required' => 'true'
                    )
                ),
                'Entity\Factory\EntityFactory' => array(
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
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setLinkManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService',
                'Entity\Factory\EntityFactoryInterface' => 'Entity\Factory\EntityFactory',
                // 'Core\Service\LanguageManagerInterface' => 'Core\Service\LanguageManager',
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Entity\Service\EntityServiceInterface' => 'EventManager',
                'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager',
                'Link\LinkManagerInterface' => 'Link\LinkManager',
                'Taxonomy\SharedTaxonomyManagerInterface' => 'Taxonomy\SharedTaxonomyManager',
                'Entity\EntityManagerInterface' => 'Entity\EntityManager'
            ),
            'Entity\Factory\EntityFactory' => array(
                'shared' => false
            )
        )
    ),
    'acl' => array(
        'Entity\LearningObjects\Exercise\Controller\TextExerciseController' => array(
            'guest' => 'deny',
            'login' => 'allow',
            'login' => array(
                'purge-revisions' => 'deny',
            ),
        )
    ),
    'router' => array(
        'routes' => array(
            'Entity\LearningObjects\Exercise\TextExercise' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/entity/exercise/text/:action/:id[/:revisionId]',
                    'defaults' => array(
                        'controller' => 'Entity\LearningObjects\Exercise\Controller\TextExerciseController',
                        'action' => 'index'
                    ),
                ),
            ),
            'Entity\LearningObjects\Solution\TextSolution' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/entity/solution/text/:action/:id[/:revisionId]',
                    'defaults' => array(
                        'controller' => 'Entity\LearningObjects\Solution\Controller\TextSolutionController',
                        'action' => 'index'
                    ),
                )
            ),
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