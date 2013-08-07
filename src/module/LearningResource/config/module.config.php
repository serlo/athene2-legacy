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
                'repository' => function($sm){
                    $class = new \LearningResource\Plugin\Repository\RepositoryPlugin();
                    $class->setRepositoryManager($sm->getServiceLocator()->get('Versioning\RepositoryManager'));
                    $class->setObjectManager($sm->getServiceLocator()->get('EntityManager'));
                    return $class;
                },
                'topicFolder' => function($sm){
                    $class = new \LearningResource\Plugin\Topic\TopicFolderPlugin();
                    $class->setSharedTaxonomyManager($sm->getServiceLocator()->get('Taxonomy\SharedTaxonomyManager'));
                    return $class;
                }
            )
        ),
        'types' => array(
            'text-exercise' => array(
                'plugins' => array(
                    array(
                        'name' => 'repository'
                    ),
                    array(
                        'name' => 'topicFolder'
                    ),
                    array(
                        'name' => 'form',
                        'options' => array(
                            'class' => 'LearningResource\Form\TextExerciseForm'
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
            'LearningResource\Plugin\Repository\Controller\RepositoryController'
        ),
        'definition' => array(
            'class' => array(
                'LearningResource\Plugin\Repository\Controller\RepositoryController' => array(
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
                                    'route' => '/repository/:action/:entity[/:revision]',
                                    'defaults' => array(
                                        'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                                        'plugin' => 'repository'
                                    )
                                )
                            )
                        )
                    ),
                    'common' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/:action[/:id]',
                            'defaults' => array(
                                'controller' => 'LearningResource\Controller\EntityController'
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
                    'controller' => 'LearningResource\Exercise\Controller\TextExerciseController',
                    'actions' => 'update',
                    'roles' => 'login'
                ),
                array(
                    'controller' => 'LearningResource\Exercise\Controller\TextExerciseController',
                    'actions' => 'show',
                    'roles' => 'guest'
                ),
                array(
                    'controller' => 'Application\LearningObject\Exercise\Controller\TextExerciseController',
                    'actions' => array(
                        'history',
                        'checkout',
                        'trash-revision',
                        'show-revision'
                    ),
                    'roles' => 'helper'
                ),
                array(
                    'controller' => 'Application\LearningObject\Exercise\Controller\TextExerciseController',
                    'actions' => array(
                        'purge-revision',
                        'create'
                    ),
                    'roles' => 'admin'
                )
            )
        )
    )
);