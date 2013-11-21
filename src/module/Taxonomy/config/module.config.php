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
namespace Taxonomy;

use Taxonomy\Router\TermRouter;
/**
 * @codeCoverageIgnore
 */
return array(
    'term_router' => array(
        'routes' => array()
    ),
    'uuid_router' => array(
        'routes' => array(
            'taxonomyTerm' => '/taxonomy/term/route/%d',
        )
    ),
    'class_resolver' => array(
        'Taxonomy\Manager\TaxonomyManagerInterface' => 'Taxonomy\Manager\TaxonomyManager',
        'Taxonomy\Entity\TaxonomyTypeInterface' => 'Taxonomy\Entity\TaxonomyType',
        'Taxonomy\Entity\TaxonomyInterface' => 'Taxonomy\Entity\Taxonomy',
        'Taxonomy\Entity\TaxonomyTermInterface' => 'Taxonomy\Entity\TaxonomyTerm',
        'Taxonomy\Service\TermServiceInterface' => 'Taxonomy\Service\TermService'
    ),
    'taxonomy' => array(
        'types' => array(
            'root' => array(
                'options' => array(
                    'allowed_parents' => array(),
                    'radix_enabled' => true,
                    'templates' => array(
                        'update' => 'taxonomy/taxonomy/update'
                    )
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'taxonomy' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/taxonomy',
                    'defaults' => array(
                        'controller' => 'Taxonomy\Controller\404',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'taxonomy' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'may_terminate' => true,
                            'route' => '/:action/:id',
                            'defaults' => array(
                                'controller' => 'Taxonomy\Controller\TaxonomyController'
                            )
                        )
                    ),
                    'term' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/term',
                            'defaults' => array(
                                'controller' => 'Taxonomy\Controller\TermController',
                                'action' => 'organize'
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'action' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:action[/:id]'
                                )
                            ),
                            'route' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/route/:id',
                                    'defaults' => array(
                                        'controller' => 'Taxonomy\Controller\TermRouterController',
                                        'action' => 'index'
                                    )
                                )
                            ),
                            'create' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/create/:taxonomy/:parent',
                                    'defaults' => array(
                                        'action' => 'create'
                                    )
                                )
                            ),
                            'order' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/order/:term',
                                    'defaults' => array(
                                        'action' => 'order'
                                    )
                                )
                            ),
                            'organize' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/organize/:id',
                                    'defaults' => array(
                                        'action' => 'organize'
                                    )
                                )
                            ),
                            'sort-associated' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/sort/:association/:term',
                                    'defaults' => array(
                                        'controller' => 'Taxonomy\Controller\TermController',
                                        'action' => 'orderAssociated'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Taxonomy\Manager\SharedTaxonomyManager' => (function ($sm)
            {
                $config = $sm->get('config');
                // $config = new \Zend\Config\Config($config['taxonomy']);
                $instance = new \Taxonomy\Manager\SharedTaxonomyManager($config['taxonomy']);
                $instance->setLanguageManager($sm->get('Language\Manager\LanguageManager'));
                $instance->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                $instance->setServiceLocator($sm);
                $instance->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                $instance->setUuidManager($sm->get('Uuid\Manager\UuidManager'));
                $instance->setTermManager($sm->get('Term\Manager\TermManager'));
                return $instance;
            }),
            'Taxonomy\Router\TermRouter' => (function ($sm)
            {
                $config = $sm->get('config');
                $instance = new TermRouter();
                $instance->setConfig($config['term_router']);
                $instance->setServiceLocator($sm);
                $instance->setSharedTaxonomyManager($sm->get('Taxonomy\Manager\SharedTaxonomyManager'));
                $instance->setRouter($sm->get('router'));
                return $instance;
            })
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Taxonomy\Controller\TermController',
            'Taxonomy\Controller\TaxonomyController',
            'Taxonomy\Controller\TermRouterController'
        ),
        'definition' => array(
            'class' => array(
                'Taxonomy\Hydrator\Navigation' => array(
                    'setLanguageManager' => array(
                        'required' => true
                    )
                ),
                'Taxonomy\Controller\TermController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    )
                ),
                'Taxonomy\Controller\TermRouterController' => array(
                    'setTermRouter' => array(
                        'required' => true
                    )
                ),
                'Taxonomy\Manager\TaxonomyManager' => array(
                    'setEntityManager' => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    )
                ),
                'Taxonomy\Controller\TaxonomyController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => true
                    )
                ),
                'Taxonomy\Controller\RouterController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => true
                    )
                ),
                'Taxonomy\Service\TermService' => array(
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setTermManager' => array(
                        'required' => true
                    ),
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setSharedTaxonomyManager' => array(
                        'required' => true
                    )
                ),
                'Taxonomy\Provider\NavigationProvider' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Taxonomy\Manager\SharedTaxonomyManagerInterface' => 'Taxonomy\Manager\SharedTaxonomyManager',
                'Taxonomy\Router\TermRouterInterface' => 'Taxonomy\Router\TermRouter'
            ),
            'Taxonomy\Manager\TaxonomyManager' => array(
                'shared' => false
            ),
            'Taxonomy\Service\TermService' => array(
                'shared' => false
            )
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