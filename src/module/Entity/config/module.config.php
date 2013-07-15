<?php
namespace Entity;

return array(
    'di' => array(
        'allowed_controllers' => array(),
        'definition' => array(
            'class' => array(
                'Entity\Service\EntityService' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
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
                'Entity\Manager\EntityManagerInterface' => 'Entity\Manager\EntityManager'
            ),
            'Entity\Service\EntityService' => array(
                'shared' => false
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Entity\Plugin\PluginManager' => (function ($sm)
            {
                $config = new \Zend\ServiceManager\Config($sm->get('config')['entity']['plugins']);
                $class = new \Entity\Plugin\PluginManager($config);
                return $class;
            }),
            'Entity\Manager\EntityManager' => (function ($sm)
            {
                $config = $sm->get('config')['entity'];
                $class = new \Entity\Manager\EntityManager($config);
                
                $class->setPluginManager($sm->get('Entity\Plugin\PluginManager'));
                $class->setServiceLocator($sm->get('ServiceManager'));
                $class->setUuidManager($sm->get('Uuid\Manager\UuidManager'));
                $class->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                
                return $class;
            })
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