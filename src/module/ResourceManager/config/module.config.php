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
namespace ResourceManager;

return array(
    'subject' => array(
        'plugins' => array(
            'factories' => array(
                'topic' => function ($sm)
                {
                    $class = new Plugin\Topic\TopicPlugin();
                    $class->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\Manager\SharedTaxonomyManager'));
                    return $class;
                },
                'curriculum' => function ($sm)
                {
                    $class = new Plugin\Curriculum\CurriculumPlugin();
                    $class->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\Manager\SharedTaxonomyManager'));
                    return $class;
                },
                'entity' => function ($sm)
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
                                    'template' => 'resource-manager/plugin/topic/entity/text-exercise'
                                ),
                                'article' => array(
                                    'labels' => array(
                                        'singular' => 'Artikel',
                                        'plural' => 'Artikel'
                                    ),
                                    'template' => 'resource-manager/plugin/topic/entity/article'
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
            'physik' => array('plugins' => array()),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
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
                                'options' => array(
                                    'route' => '/{curriculum}',
                                    'defaults' => array(
                                        'controller' => __NAMESPACE__ . '\Plugin\Curriculum\Controller\CurriculumController',
                                        'plugin' => 'curriculum'
                                    )
                                ),
                                'child_routes' => array(
                                    'may_terminate' => true,
                                    'show' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/:curriculum[/:path]',
                                            'defaults' => array(
                                                'controller' => __NAMESPACE__ . '\Plugin\Curriculum\Controller\CurriculumController',
                                                'action' => 'show'
                                            )
                                        ),
                                        'constraints' => array(
                                            'path' => '(.)+'
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
            __NAMESPACE__ . '\Plugin\Topic\Controller\TopicController',
            __NAMESPACE__ . '\Plugin\Curriculum\Controller\CurriculumController',
            __NAMESPACE__ . '\Plugin\Entity\Controller\EntityController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Plugin\Topic\Controller\TopicController' => array(
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
                )
            )
        ),
        'instances' => array(
            'Subject\SubjectManagerInterface' => 'Subject\SubjectManager'
        )
    )
);