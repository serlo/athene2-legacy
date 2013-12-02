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

use Entity\Plugin;
return array(
    'entity' => array(
        'plugins' => array(
            'factories' => array(
                'repository' => function ($sm)
                {
                    $instance = new Plugin\Repository\RepositoryPlugin();
                    $instance->setRepositoryManager($sm->getServiceLocator()
                        ->get('Versioning\RepositoryManager'));
                    $instance->setObjectManager($sm->getServiceLocator()
                        ->get('EntityManager'));
                    $instance->setAuthenticationService($sm->getServiceLocator()
                        ->get('Zend\Authentication\AuthenticationService'));
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    $instance->setRouter($sm->getServiceLocator()
                        ->get('Router'));
                    $instance->setUserManager($sm->getServiceLocator()
                        ->get('User\Manager\UserManager'));
                    $instance->setUuidManager($sm->getServiceLocator()
                        ->get('Uuid\Manager\UuidManager'));
                    return $instance;
                },
                'license' => function ($sm)
                {
                    $instance = new Plugin\License\LicensePlugin();
                    $instance->setLicenseManager($sm->getServiceLocator()->get('License\Manager\LicenseManager'));
                    return $instance;
                },
                'learningResource' => function ($sm)
                {
                    $instance = new Plugin\LearningResource\LearningResourcePlugin();
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    return $instance;
                },
                'link' => function ($sm)
                {
                    $instance = new Plugin\Link\LinkPlugin();
                    $instance->setSharedLinkManager($sm->getServiceLocator()
                        ->get('Link\Manager\SharedLinkManager'));
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    return $instance;
                },
                'provider' => function ($sm)
                {
                    $instance = new Plugin\Provider\ProviderPlugin();
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    return $instance;
                },
                'page' => function ($sm)
                {
                    $instance = new Plugin\Page\PagePlugin();
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    return $instance;
                },
                'taxonomy' => function ($sm)
                {
                    $instance = new Plugin\Taxonomy\TaxonomyPlugin();
                    $instance->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\Manager\SharedTaxonomyManager'));
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    $instance->setObjectManager($sm->getServiceLocator()
                        ->get('EntityManager'));
                    return $instance;
                },
                'pathauto' => function ($sm)
                {
                    $instance = new Plugin\Pathauto\PathautoPlugin();
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    $instance->setServiceLocator($sm->getServiceLocator());
                    $instance->setTokenizer($sm->getServiceLocator()
                        ->get('Token\Tokenizer'));
                    $instance->setAliasManager($sm->getServiceLocator()
                        ->get('Alias\AliasManager'));
                    $instance->setLanguageManager($sm->getServiceLocator()
                        ->get('Language\Manager\LanguageManager'));
                    return $instance;
                },
                'aggregator' => function ($sm)
                {
                    $instance = new Plugin\Aggregate\AggregatePlugin();
                    
                    $topicAggregator = new Plugin\Aggregate\Aggregator\TopicAggregator();
                    $topicAggregator->setRouter($sm->getServiceLocator()
                        ->get('router'));
                    $instance->addAggregator($topicAggregator);
                    
                    return $instance;
                },
                'metadata' => function ($sm)
                {
                    $instance = new Plugin\Metadata\MetadataPlugin();
                    
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    $instance->setMetadataManager($sm->getServiceLocator()
                        ->get('Metadata\Manager\MetadataManager'));
                    
                    return $instance;
                }
            )
        )
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
                    'plugin' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '',
                            'defaults' => array()
                        ),
                        'child_routes' => array(
                            'repository' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/repository',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\Plugin\Repository\Controller\RepositoryController',
                                        'plugin' => 'repository'
                                    )
                                ),
                                'child_routes' => array(
                                    'checkout' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/checkout/:entity/:revision',
                                            'defaults' => array(
                                                'action' => 'checkout'
                                            )
                                        )
                                    ),
                                    'compare' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/compare/:entity/:revision',
                                            'defaults' => array(
                                                'action' => 'compare'
                                            )
                                        )
                                    ),
                                    'history' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/history/:entity',
                                            'defaults' => array(
                                                'action' => 'history'
                                            )
                                        )
                                    ),
                                    'add-revision' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/add-revision/:entity',
                                            'defaults' => array(
                                                'action' => 'addRevision'
                                            )
                                        )
                                    )
                                )
                            ),
                            'link' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/link',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\Plugin\Link\Controller\LinkController',
                                        'plugin' => 'link'
                                    )
                                ),
                                'child_routes' => array(
                                    'order' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/order/:scope/:entity',
                                            'defaults' => array(
                                                'action' => 'orderChildren'
                                            )
                                        )
                                    ),
                                    'move' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/move/:scope/:entity[/:from]',
                                            'defaults' => array(
                                                'action' => 'move'
                                            )
                                        )
                                    )
                                )
                            ),
                            'page' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/view/:entity',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\Plugin\Page\Controller\PageController',
                                        'plugin' => 'page',
                                        'action' => 'index'
                                    )
                                )
                            ),
                            'taxonomy' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/taxonomy',
                                    'defaults' => array(
                                        'plugin' => 'taxonomy'
                                    ),
                                    'may_terminate' => false
                                ),
                                'child_routes' => array(
                                    'update' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/update/:entity',
                                            'defaults' => array(
                                                'controller' => __NAMESPACE__ . '\Plugin\Taxonomy\Controller\TaxonomyController',
                                                'action' => 'update'
                                            )
                                        ),
                                        'may_terminate' => true
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Plugin\Repository\Controller\RepositoryController',
            __NAMESPACE__ . '\Plugin\Page\Controller\PageController',
            __NAMESPACE__ . '\Plugin\Taxonomy\Controller\TaxonomyController',
            __NAMESPACE__ . '\Plugin\Link\Controller\LinkController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Plugin\Pathauto\Provider\TokenProvider' => array(
                    'setServiceLocator' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Plugin\Link\Controller\LinkController' => array(
                    'setEntityManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Plugin\Taxonomy\Controller\TaxonomyController' => array(
                    'setEntityManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Plugin\Repository\Controller\RepositoryController' => array(
                    'setEntityManager' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Plugin\Page\Controller\PageController' => array(
                    'setAliasManager' => array(
                        'required' => true
                    ),
                    'setEntityManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Plugin\Taxonomy\Listener\EntityControllerListener' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => true
                    )
                ),
                'Entity\Plugin\LearningResource\Listener\EntityControllerListener' => array(
                    'setMetadataManager' => array(
                        'required' => true
                    )
                ),
                'Entity\Plugin\License\Listener\EntityControllerListener' => array(
                    'setLicenseManager' => array(
                        'required' => true
                    )
                ),
                'Entity\Plugin\LearningResource\Listener\EntityTaxonomyPluginControllerListener' => array(
                    'setMetadataManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Entity\EntityManagerInterface' => 'Entity\EntityManager'
            )
        )
    )
);