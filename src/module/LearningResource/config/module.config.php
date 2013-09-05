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
                    $instance = new \LearningResource\Plugin\Repository\RepositoryPlugin();
                    $instance->setRepositoryManager($sm->getServiceLocator()
                        ->get('Versioning\RepositoryManager'));
                    $instance->setObjectManager($sm->getServiceLocator()
                        ->get('EntityManager'));
                    $instance->setAuthService($sm->getServiceLocator()
                        ->get('Auth\Service\AuthService'));
                    return $instance;
                },
                'topicFolder' => function  ($sm)
                {
                    $instance = new \LearningResource\Plugin\Taxonomy\TopicFolderPlugin();
                    $instance->setSharedTaxonomyManager($sm->getServiceLocator()
                        ->get('Taxonomy\Manager\SharedTaxonomyManager'));
                    return $instance;
                },
                'link' => function  ($sm)
                {
                    $instance = new \LearningResource\Plugin\Link\LinkPlugin();
                    $instance->setLinkManager($sm->getServiceLocator()
                        ->get('Link\Manager\SharedLinkManager')->get('link', 'Entity\Entity\EntityLinkType'));
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    return $instance;
                },
                'dependency' => function  ($sm)
                {
                    $instance = new \LearningResource\Plugin\Link\LinkPlugin();
                    $instance->setLinkManager($sm->getServiceLocator()
                        ->get('Link\Manager\SharedLinkManager')->get('dependency', 'Entity\Entity\EntityLinkType'));
                    $instance->setEntityManager($sm->getServiceLocator()
                        ->get('Entity\Manager\EntityManager'));
                    return $instance;
                }
            )
        ),
        'types' => array(
            'text-exercise' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\TextExerciseForm',
                            'field_order' => array(
                                'title',
                                'content'
                            )
                        )
                    ),
                    'topicFolder' => array(
                        'plugin' => 'topicFolder'
                    ),
                    'solution' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array('text-solution'),
                            'association' => 'one-to-one'
                        )
                    )
                )
            ),
            'exercise-group' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\TextExerciseGroupForm',
                            'field_order' => array(
                                'content'
                            )
                        )
                    ),
                    'topicFolder' => array(
                        'plugin' => 'topicFolder'
                    ),
                    'exercises' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array('grouped-text-exercise'),
                            'association' => 'one-to-many'
                        )
                    ),
                ),
            ),
            'grouped-text-exercise' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\GroupedTextExerciseForm',
                            'field_order' => array(
                                'hint',
                                'content'
                            )
                        )
                    ),
                    'group' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array('exercise-group'),
                            'association' => 'one-to-one'
                        )
                    ),
                    'solution' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array('text-solution'),
                            'association' => 'one-to-one'
                        )
                    )
                ),
            ),
            'text-solution' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\TextSolutionForm',
                            'field_order' => array(
                                'title',
                                'hint',
                                'content'
                            )
                        )
                    ),
                    'exercise' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array('text-exercise'),
                            'association' => 'one-to-one'
                        )
                    )
                )
            ),
            'article' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\ArticleForm',
                            'field_order' => array(
                                'title',
                                'content'
                            )
                        )
                    )
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
            'ZfcRbac\Firewall\Controller' => array(
                array(
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => array(
                        'compare',
                        'history'
                    ),
                    'roles' => 'guest'
                ),
                array(
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => 'add-revision',
                    'roles' => 'login'
                ),
                array(
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => array(
                        'trash-revision',
                        'checkout'
                    ),
                    'roles' => 'helper'
                ),
                array(
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => 'purge-revision',
                    'roles' => 'admin'
                ),
                
                array(
                    'controller' => 'LearningResource\Plugin\Taxonomy\Controller\TopicFolderController',
                    'actions' => array(
                        'set-topic',
                        'topic-dialog'
                    ),
                    'roles' => 'helper'
                )
            )
        )
    )
    /*'zfcrbac' => array(
        'firewalls' => array(
        ),
    )*/
);