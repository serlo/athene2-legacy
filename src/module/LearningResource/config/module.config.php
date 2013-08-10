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
return array(
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
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'entity' => array(
        'plugins' => array(
            'factories' => array(
                'repository' => function  ($sm)
                {
                    $class = new \LearningResource\Plugin\Repository\RepositoryPlugin();
                    $class->setRepositoryManager($sm->getServiceLocator()
                        ->get('Versioning\RepositoryManager'));
                    $class->setObjectManager($sm->getServiceLocator()
                        ->get('EntityManager'));
                    $class->setAuthService($sm->getServiceLocator()
                        ->get('Auth\Service\AuthService'));
                    return $class;
                },
                'topicFolder' => function  ($sm)
                {
                    $class = new \LearningResource\Plugin\Taxonomy\TopicFolderPlugin();
                    $class->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\SharedTaxonomyManager'));
                    return $class;
                },
                'link' => function  ($sm)
                {
                    $class = new \LearningResource\Plugin\Link\LinkPlugin();
                    $class->setLinkManager($sm->getServiceLocator()
                        ->get('Link\LinkManager'));
                    $class->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    return $class;
                }
            )
        ),
        'types' => array(
            'text-exercise' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\TextExerciseForm'
                        )
                    ),
                    'topicFolder' => array(
                        'plugin' => 'topicFolder'
                    ),
                    'solution' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'to_type' => 'text-solution'
                        )
                    )
                )
            ),
            'text-solution' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\TextSolutionForm'
                        )
                    ),
                    'exercise' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'to_type' => 'text-exercise'
                        )
                    )
                )
            ),
            'article' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\ArticleForm'
                        )
                    ),
                )
            )
        ),
        'instances' => array(
            'Entity\Service\EntityServiceInterface' => 'Entity\Service\EntityService',
            'Entity\Entity\EntityInterface' => 'Entity\Entity\Entity',
            'Entity\Entity\TypeInterface' => 'Entity\Entity\Type'
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'LearningResource\Plugin\Repository\Controller\RepositoryController',
            'LearningResource\Plugin\Taxonomy\Controller\TopicFolderController'
        ),
        'definition' => array(
            'class' => array(
                'LearningResource\Plugin\Repository\Controller\RepositoryController' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    )
                ),
                'LearningResource\Plugin\Taxonomy\Controller\TopicFolderController' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Entity\EntityManagerInterface' => 'Entity\EntityManager'
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'entity' => array(
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
                                    'route' => '/repository/:action/:entity[/:revision]',
                                    'defaults' => array(
                                        'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                                        'plugin' => 'repository'
                                    )
                                )
                            ),
                            'topic-folder' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/topic-folder/:action/:entity[/:term]',
                                    'defaults' => array(
                                        'controller' => 'LearningResource\Plugin\Taxonomy\Controller\TopicFolderController',
                                        'plugin' => 'topicFolder'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    ),
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Route' => array(
                array(
                    'route' => 'entity/plugin/repository',
                    'actions' => 'add-revision',
                    'roles' => 'login'
                ),
                array(
                    'route' => 'entity/plugin/repository',
                    'actions' => array('checkout', 'trash-revision'),
                    'roles' => 'helper'
                ),
                array(
                    'route' => 'entity/plugin/repository',
                    'actions' => 'purge-revision',
                    'roles' => 'admin'
                ),
            ),
            'ZfcRbac\Firewall\Controller' => array(
                array(
                    'controller' => 'LearningResource\Exercise\Controller\TextExerciseController',
                    'actions' => 'update',
                    'roles' => 'login'
                ),
            )
        )
    )
    /*'zfcrbac' => array(
        'firewalls' => array(
        ),
    )*/
);