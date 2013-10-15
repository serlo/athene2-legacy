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
namespace Subject;

use Zend\ServiceManager\ServiceLocatorInterface;
use Entity\Collection\EntityCollection;
return array(
    'taxonomy' => array(
        'associations' => array(
            'entities' => function  (ServiceLocatorInterface $sm, $collection)
            {
                return new EntityCollection($collection, $sm->get('Entity\Manager\EntityManager'));
            }
        ),
        'types' => array(
            'topic-folder' => array(
                'options' => array(
                    'allowed_associations' => array(
                        'entities'
                    ),
                    'allowed_parents' => array(
                        'topic',
                        'topic-folder'
                    ),
                    'radix_enabled' => false
                )
            ),
            'topic' => array(
                'options' => array(
                    'allowed_parents' => array(
                        'subject',
                        'topic'
                    ),
                    'radix_enabled' => false
                )
            ),
            'subject' => array(
                'options' => array(
                    'templates' => array(
                        'update' => 'taxonomy/taxonomy/update'
                    ),
                    'radix_enabled' => false
                )
            ),
            'school-type' => array(
                'options' => array(
                    'templates' => array(
                        'update' => 'taxonomy/taxonomy/update'
                    ),
                    'allowed_parents' => array(
                        'subject',
                        'school-type'
                    ),
                    'radix_enabled' => false
                )
            ),
            'curriculum' => array(
                'options' => array(
                    'allowed_parents' => array(
                        'school-type'
                    ),
                    'radix_enabled' => false
                )
            ),
            'curriculum-folder' => array(
                'options' => array(
                    'allowed_associations' => array(
                        'entities'
                    ),
                    'allowed_parents' => array(
                        'curriculum'
                    ),
                    'radix_enabled' => false
                )
            )
        )
    ),
    'subject' => array(
        'plugins' => array(
            'factories' => array(
                'taxonomy' => function  ($sm)
                {
                    $class = new Plugin\Taxonomy\TaxonomyPlugin();
                    $class->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\Manager\SharedTaxonomyManager'));
                    return $class;
                },/*
                'topic' => function  ($sm)
                {
                    $class = new Plugin\Topic\TopicPlugin();
                    $class->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\Manager\SharedTaxonomyManager'));
                    return $class;
                },*/
                'curriculum' => function  ($sm)
                {
                    $class = new Plugin\Curriculum\CurriculumPlugin();
                    $class->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\Manager\SharedTaxonomyManager'));
                    return $class;
                },
                'taxonomyFilter' => function  ($sm)
                {
                    $class = new Plugin\Curriculum\CurriculumPlugin();
                    $class->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\Manager\SharedTaxonomyManager'));
                    $class->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    return $class;
                },
                'entity' => function  ($sm)
                {
                    $class = new Plugin\Entity\EntityPlugin();
                    $class->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    $class->setObjectManager($sm->getServiceLocator()
                        ->get('EntityManager'));
                    return $class;
                }
            )
        ),
        'instances' => array(
            array(
                'name' => 'mathe',
                'language' => 'de',
                'plugins' => array(
                    array(
                        'name' => 'topic',
                        'plugin' => 'taxonomy',
                        'options' => array(
                            'taxonomy' => 'topic',
                            'taxonomy_parent' => 'subject',
                            'route' => 'subject/plugin/taxonomy/topic',
                            'templates' => array(
                                'index' => 'subject/plugin/taxonomy/custom/topic/index'
                            ),
                            'entity_types' => array(
                                'text-exercise' => array(
                                    'labels' => array(
                                        'singular' => 'Aufgabe',
                                        'plural' => 'Aufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Artikel',
                                        'plural' => 'Artikel'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/article'
                                ),
                                'exercise-group' => array(
                                    'labels' => array(
                                        'singular' => 'Gruppenaufgabe',
                                        'plural' => 'Gruppenaufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/exercise-group'
                                ),
                                'video' => array(
                                    'labels' => array(
                                        'singular' => 'Video',
                                        'plural' => 'Videos'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/video'
                                ),
                                'module' => array(
                                    'labels' => array(
                                        'singular' => 'Modul',
                                        'plural' => 'Module'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/module'
                                ),
                            )
                        )
                    ),
                    array(
                        'name' => 'curriculum',
                        'plugin' => 'taxonomy',
                        'options' => array(
                            'taxonomy' => 'school-type',
                            'taxonomy_parent' => 'subject',
                            'route' => 'subject/plugin/taxonomy/curriculum',
                            'templates' => array(
                                'index' => 'subject/plugin/taxonomy/custom/curriculum/index'
                            ),
                            'entity_types' => array(
                                'text-exercise' => array(
                                    'labels' => array(
                                        'singular' => 'Aufgabe',
                                        'plural' => 'Aufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Artikel',
                                        'plural' => 'Artikel'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/article'
                                ),
                                'exercise-group' => array(
                                    'labels' => array(
                                        'singular' => 'Gruppenaufgabe',
                                        'plural' => 'Gruppenaufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/exercise-group'
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'entity',
                        'plugin' => 'entity'
                    )
                )
            ),
            array(
                'name' => 'physik',
                'language' => 'de',
                'plugins' => array(
                    array(
                        'name' => 'topic',
                        'plugin' => 'taxonomy',
                        'options' => array(
                            'taxonomy' => 'topic',
                            'taxonomy_parent' => 'subject',
                            'route' => 'subject/plugin/taxonomy/topic',
                            'templates' => array(
                                'index' => 'subject/plugin/taxonomy/custom/topic/index'
                            ),
                            'entity_types' => array(
                                'text-exercise' => array(
                                    'labels' => array(
                                        'singular' => 'Aufgabe',
                                        'plural' => 'Aufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Artikel',
                                        'plural' => 'Artikel'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/article'
                                ),
                                'exercise-group' => array(
                                    'labels' => array(
                                        'singular' => 'Gruppenaufgabe',
                                        'plural' => 'Gruppenaufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/exercise-group'
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'curriculum',
                        'plugin' => 'taxonomy',
                        'options' => array(
                            'taxonomy' => 'school-type',
                            'taxonomy_parent' => 'subject',
                            'route' => 'subject/plugin/taxonomy/curriculum',
                            'templates' => array(
                                'index' => 'subject/plugin/taxonomy/custom/curriculum/index'
                            ),
                            'entity_types' => array(
                                'text-exercise' => array(
                                    'labels' => array(
                                        'singular' => 'Aufgabe',
                                        'plural' => 'Aufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Artikel',
                                        'plural' => 'Artikel'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/article'
                                ),
                                'exercise-group' => array(
                                    'labels' => array(
                                        'singular' => 'Gruppenaufgabe',
                                        'plural' => 'Gruppenaufgaben'
                                    ),
                                    'template' => 'subject/plugin/taxonomy/entity/exercise-group'
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'entity',
                        'plugin' => 'entity'
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'class_resolver' => array(
        __NAMESPACE__ . '\Service\SubjectServiceInterface' => __NAMESPACE__ . '\Service\SubjectService',
        __NAMESPACE__ . '\Entity\SubjectInterface' => 'Taxonomy\Entity\TermTaxonomy',
        __NAMESPACE__ . '\Entity\SubjectTypeInterface' => __NAMESPACE__ . '\Entity\SubjectType'
    ),
    'service_manager' => array(
        'factories' => array(
            __NAMESPACE__ . '\Plugin\PluginManager' => (function  ($sm)
            {
                $config = $sm->get('config');
                $config = new \Zend\ServiceManager\Config($config['subject']['plugins']);
                $class = new \Subject\Plugin\PluginManager($config);
                return $class;
            }),
            __NAMESPACE__ . '\Manager\SubjectManager' => (function  ($sm)
            {
                $config = $sm->get('config');
                $class = new \Subject\Manager\SubjectManager($config['subject']);
                
                $class->setPluginManager($sm->get('Subject\Plugin\PluginManager'));
                $class->setServiceLocator($sm->get('ServiceManager'));
                $class->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                $class->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                $class->setLanguageManager($sm->get('Language\Manager\LanguageManager'));
                $class->setSharedTaxonomyManager($sm->get('Taxonomy\Manager\SharedTaxonomyManager'));
                
                return $class;
            })
        )
    ),
    'router' => array(
        'routes' => array(
            'subject' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/{subject}[/:subject]',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Plugin\Home\Controller\HomeController',
                        'action' => 'index'
                    )
                ),
                'child_routes' => array(
                    'plugin' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => ''
                        ),
                        'child_routes' => array(
                            'taxonomy' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/{taxonomy}',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\Plugin\Taxonomy\Controller\TaxonomyController',
                                        'action' => 'index'
                                    )
                                ),
                                'child_routes' => array(
                                    'topic' => array(
                                        'may_terminate' => true,
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/{topic}[/:path/]',
                                            'defaults' => array(
                                                'plugin' => 'topic'
                                            ),
                                            'constraints' => array(
                                                'path' => '(.)+'
                                            )
                                        )
                                    ),
                                    'curriculum' => array(
                                        'may_terminate' => true,
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/{curriculum}[/:path/]',
                                            'defaults' => array(
                                                'plugin' => 'curriculum'
                                            ),
                                            'constraints' => array(
                                                'path' => '(.)+'
                                            )
                                        )
                                    )
                                )
                            ),
                            'entity' => array(
                                'may_terminate' => true,
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/entity/:action',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\Plugin\Entity\Controller\EntityController',
                                        'action' => 'index',
                                        'plugin' => 'entity'
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
            // __NAMESPACE__
            // .
            // '\Application\DefaultSubject\Controller\TopicController',
            __NAMESPACE__ . '\Plugin\Taxonomy\Controller\TaxonomyController',
            // __NAMESPACE__
            // .
            // '\Plugin\Topic\Controller\TopicController',
            // __NAMESPACE__
            // .
            // '\Plugin\Curriculum\Controller\CurriculumController',
            // __NAMESPACE__
            // .
            // '\Plugin\Curriculum\Controller\EntityController',
            __NAMESPACE__ . '\Plugin\Entity\Controller\EntityController',
            __NAMESPACE__ . '\Plugin\Home\Controller\HomeController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Plugin\Home\Controller\HomeController' => array(
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Plugin\Taxonomy\Controller\TaxonomyController' => array(
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Plugin\Entity\Controller\EntityController' => array(
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Hydrator\RouteStack' => array(
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Hydrator\Route' => array(
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Hydrator\Navigation' => array(
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Manager\SubjectManager' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Service\SubjectService' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    ),
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    ),
                    'setPluginManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\SubjectManagerInterface' => __NAMESPACE__ . '\Manager\SubjectManager',
                __NAMESPACE__ . '\Plugin\PluginManagerInterface' => __NAMESPACE__ . '\Plugin\PluginManager'
            ),
            __NAMESPACE__ . '\Service\SubjectService' => array(
                'shared' => false
            )
        )
    ),
    'view_helpers' => array(
        'factories' => array()
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