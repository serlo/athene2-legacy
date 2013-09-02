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

return array(
    'subject' => array(
        'plugins' => array(
            'factories' => array(
                'topic' => function  ($sm)
                {
                    $class = new Plugin\Topic\TopicPlugin();
                    $class->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\Manager\SharedTaxonomyManager'));
                    return $class;
                },
                'curriculum' => function  ($sm)
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
            'mathe' => array(
                'plugins' => array(
                    array(
                        'name' => 'topic',
                        'options' => array(
                            'entity_types' => array(
                                'text-exercise' => array(
                                    'labels' => array(
                                        'singular' => 'Aufgabe',
                                        'plural' => 'Aufgaben'
                                    ),
                                    'template' => 'subject/plugin/topic/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Artikel',
                                        'plural' => 'Artikel'
                                    ),
                                    'template' => 'subject/plugin/topic/entity/article'
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'entity'
                    ),
                    array(
                        'name' => 'curriculum'
                    )
                )
            ),
            'physik' => array(
                'plugins' => array(
                    array(
                        'name' => 'topic',
                        'options' => array(
                            'entity_types' => array(
                                'text-exercise' => array(
                                    'labels' => array(
                                        'singular' => 'Aufgabe',
                                        'plural' => 'Aufgaben'
                                    ),
                                    'template' => 'subject/plugin/topic/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Artikel',
                                        'plural' => 'Artikel'
                                    ),
                                    'template' => 'subject/plugin/topic/entity/article'
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'entity'
                    )
                )
            ),
            'math' => array(
                'plugins' => array(
                    array(
                        'name' => 'topic',
                        'options' => array(
                            'entity_types' => array(
                                'text-exercise' => array(
                                    'labels' => array(
                                        'singular' => 'Exercise',
                                        'plural' => 'Exercises'
                                    ),
                                    'template' => 'subject/plugin/topic/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Article',
                                        'plural' => 'Articles'
                                    ),
                                    'template' => 'subject/plugin/topic/entity/article'
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'entity'
                    )
                )
            ),
            'physics' => array(
                'plugins' => array()
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
        __NAMESPACE__ . '\Entity\SubjectEntityInterface' => __NAMESPACE__ . '\Entity\Subject',
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
                $class = new \Subject\Manager\SubjectManager($config['subject']['instances']);
                
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
                        'controller' => __NAMESPACE__ . '\Provider\Home\Controller\HomeController',
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
                            'topic' => array(
                                'may_terminate' => true,
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/{topic}/:path',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\Plugin\Topic\Controller\TopicController',
                                        'action' => 'index',
                                        'plugin' => 'topic'
                                    ),
                                    'constraints' => array(
                                        'path' => '(.)+'
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
                            ),
                            'curriculum' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/{curriculum}/[:curriculum]',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\Plugin\Curriculum\Controller\CurriculumController',
                                        'plugin' => 'curriculum',
                                        'action' => 'index'
                                    )
                                ),
                                'child_routes' => array(
                                    'topic' => array(
                                        'may_terminate' => true,
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/{topic}/[:path]',
                                            'defaults' => array(
                                                'controller' => __NAMESPACE__ . '\Plugin\Curriculum\Controller\CurriculumController',
                                                'action' => 'topic'
                                            ),
                                            'constraints' => array(
                                                'path' => '(.)+'
                                            )
                                        )
                                    ),
                                    'entity' => array(
                                        'may_terminate' => true,
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/entity/:action/:entity',
                                            'defaults' => array(
                                                'controller' => __NAMESPACE__ . '\Plugin\Curriculum\Controller\EntityController',
                                                'action' => 'index'
                                            ),
                                        )
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
            __NAMESPACE__ . '\Application\DefaultSubject\Controller\TopicController',
            __NAMESPACE__ . '\Plugin\Topic\Controller\TopicController',
            __NAMESPACE__ . '\Plugin\Curriculum\Controller\CurriculumController',
            __NAMESPACE__ . '\Plugin\Curriculum\Controller\EntityController',
            __NAMESPACE__ . '\Plugin\Entity\Controller\EntityController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Plugin\Topic\Controller\TopicController' => array(
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Plugin\Curriculum\Controller\EntityController' => array(
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Plugin\Curriculum\Controller\CurriculumController' => array(
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Plugin\Entity\Controller\EntityController' => array(
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
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    ),
                    'setEntityManager' => array(
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