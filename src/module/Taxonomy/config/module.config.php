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

return array(
    'class_resolver' => array(
        'Taxonomy\Manager\TermManagerInterface' => 'Taxonomy\Manager\TermManager',
        'Taxonomy\Entity\TaxonomyTypeInterface' => 'Taxonomy\Entity\TaxonomyType',
        'Taxonomy\Entity\TaxonomyEntityInterface' => 'Taxonomy\Entity\Taxonomy',
        'Taxonomy\Entity\TermTaxonomyEntityInterface' => 'Taxonomy\Entity\TermTaxonomy',
        'Taxonomy\Service\TermServiceInterface' => 'Taxonomy\Service\TermService',
        'Taxonomy\Entity\TermTaxonomyEntityInterface' => 'Taxonomy\Entity\TermTaxonomy'
    ),
    'taxonomy' => array(
        'types' => array(
            'root' => array(
                'options' => array(
                    'allowed_parents' => array(
                    ),
                    'radix_enabled' => true
                )
            ),
            )),
    'router' => array(
        'routes' => array(
            'taxonomy' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/taxonomy/',
                    'defaults' => array(
                        'controller' => 'Taxonomy\Controller\404',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'taxonomy' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => ':action/:id',
                            'defaults' => array(
                                'controller' => 'Taxonomy\Controller\TaxonomyController'
                            )
                        )
                    ),
                    'term' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => 'term/:action/[:id]',
                            'defaults' => array(
                                'controller' => 'Taxonomy\Controller\TermController',
                                'action' => 'index'
                            )
                        )
                    )
                )
            )
        )
        
    ),
    'service_manager' => array(
        'factories' => array(
            'Taxonomy\Manager\SharedTaxonomyManager' => (function  ($sm)
            {
                $config = $sm->get('config');
                $config = new \Zend\Config\Config($config['taxonomy']);
                $instance = new \Taxonomy\Manager\SharedTaxonomyManager($config);
                $instance->setLanguageManager($sm->get('Language\Manager\LanguageManager'));
                $instance->setObjectManager($sm->get('EntityManager'));
                $instance->setServiceLocator($sm);
                $instance->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                return $instance;
            })
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Taxonomy\Controller\TermController',
            'Taxonomy\Controller\TaxonomyController'
        ),
        'definition' => array(
            'class' => array(
                'Taxonomy\Controller\TermController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    )
                ),
                'Taxonomy\Manager\TermManager' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setTermManager' => array(
                        'required' => 'true'
                    ),
                    'setClassResolver' => array(
                        'required' => 'true'
                    ),
                    'setUuidManager' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    )
                ),
                'Taxonomy\Controller\TermController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    )
                ),
                'Taxonomy\Controller\TaxonomyController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    )
                ),
                'Taxonomy\Service\TermService' => array(
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setTermManager' => array(
                        'required' => 'true'
                    ),
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Term\Manager\TermManagerInterface' => 'Term\Manager\TermManager',
                'Uuid\Manager\UuidManagerInterface' => 'Uuid\Manager\UuidManager',
                'Taxonomy\Manager\SharedTaxonomyManagerInterface' => 'Taxonomy\Manager\SharedTaxonomyManager'
            // 'Auth\Service\AuthServiceInterface'
            // =>
            // 'Auth\Service\AuthService',
            // 'Entity\Service\EntityServiceInterface'
            // =>
            // 'EventManager',
            // 'Versioning\RepositoryManagerInterface'
            // =>
            // 'Versioning\RepositoryManager',
            // 'SharedTaxonomyManagerInterface'
            // =>
            // 'SharedTaxonomyManager'
                        ),
            'Taxonomy\Manager\TermManager' => array(
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