<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity;

return array(
    'uuid_router' => array(
        'routes' => array(
            'entity' => '/entity/view/%d',
            'entityRevision' => '/entity/repository/compare/%d'
        )
    ),
    'class_resolver' => array(
        'Entity\Entity\EntityInterface' => 'Entity\Entity\Entity',
        'Entity\Entity\TypeInterface' => 'Entity\Entity\Type',
        'Entity\Service\EntityServiceInterface' => 'Entity\Service\EntityService'
    ),
    'router' => array(
        'routes' => array(
            'entity' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/entity',
                    'defaults' => array()
                ),
                'child_routes' => array(
                    'create' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/create/:type',
                            'defaults' => array(
                                'controller' => 'Entity\Controller\EntityController',
                                'action' => 'create'
                            )
                        )
                    ),
                    'trash' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/trash/:entity',
                            'defaults' => array(
                                'controller' => 'Entity\Controller\EntityController',
                                'action' => 'trash'
                            )
                        )
                    ),
                    'restore' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/restore/:entity',
                            'defaults' => array(
                                'controller' => 'Entity\Controller\EntityController',
                                'action' => 'restore'
                            )
                        )
                    ),
                    'purge' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/purge/:entity',
                            'defaults' => array(
                                'controller' => 'Entity\Controller\EntityController',
                                'action' => 'purge'
                            )
                        )
                    )
                )
            )
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Entity\Controller\EntityController'
        ),
        'definition' => array(
            'class' => array(
                'Entity\Controller\EntityController' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setUserManager' => array(
                        'required' => 'true'
                    )
                ),
                'Entity\Service\EntityService' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setRepositoryManager' => array(
                        'required' => 'true'
                    ),
                    'setAuthService' => array(
                        'required' => 'true'
                    ),
                    'setSharedTaxonomyManager' => array(
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
                $config = $sm->get('config');
                $config = new \Zend\ServiceManager\Config($config['entity']['plugins']);
                $class = new \Entity\Plugin\PluginManager($config);
                return $class;
            }),
            'Entity\Manager\EntityManager' => (function ($sm)
            {
                $config = $sm->get('config');
                $class = new \Entity\Manager\EntityManager();
                
                $class->setConfig($config['entity']);
                $class->setPluginManager($sm->get('Entity\Plugin\PluginManager'));
                $class->setServiceLocator($sm->get('ServiceManager'));
                $class->setUuidManager($sm->get('Uuid\Manager\UuidManager'));
                $class->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                $class->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                
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