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
namespace LearningResource;

use Entity\Service\EntityServiceInterface;
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
                            'fields' => array(
                                'title',
                                'content'
                            )
                        )
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'solution' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'text-solution' => array(
                                    'inversed_by' => 'exercise',
                                    'owning' => true
                                )
                            ),
                            'type' => 'link',
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
                            'fields' => array(
                                'content'
                            )
                        )
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'exercises' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'grouped-text-exercise' => array(
                                    'inversed_by' => 'group'
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-many'
                        )
                    )
                )
            ),
            'grouped-text-exercise' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\GroupedTextExerciseForm',
                            'fields' => array(
                                'hint',
                                'content'
                            )
                        )
                    ),
                    'group' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'exercise-group' => array(
                                    'inversed_by' => 'exercises'
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-many'
                        )
                    ),
                    'solution' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'exercise-solution' => array(
                                    'inversed_by' => 'exercise',
                                    'owning' => true
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-one'
                        )
                    )
                )
            ),
            'text-solution' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\TextSolutionForm',
                            'fields' => array(
                                'title',
                                'hint',
                                'content'
                            )
                        )
                    ),
                    'exercise' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'text-exercise' => array(
                                    'inversed_by' => 'solution',
                                    'owning' => false
                                ),
                                'grouped-text-exercise' => array(
                                    'inversed_by' => 'solution',
                                    'owning' => false
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-one'
                        )
                    )
                )
            ),
            'video' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\VideoForm',
                            'fields' => array(
                                'title',
                                'content'
                            )
                        )
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'learning-resource/plugin/page/video',
                            'fields' => array(
                                'title',
                                'content'
                            )
                        )
                    ),
                    'pathauto' => array(
                        'plugin' => 'pathauto',
                        'options' => array(
                            'tokenize' => '{subject}/{type}/{title}'
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider',
                        'options' => array(
                            'fields' => array(
                                'title' => function (EntityServiceInterface $es)
                                {
                                    return $es->repository()
                                        ->getCurrentRevision()
                                        ->get('title');
                                },
                                'content' => function (EntityServiceInterface $es)
                                {
                                    return $es->repository()
                                        ->getCurrentRevision()
                                        ->get('content');
                                }
                            )
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
                            'fields' => array(
                                'title',
                                'content'
                            )
                        )
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'fields' => array(
                                'title',
                                'content'
                            )
                        )
                    ),
                    'pathauto' => array(
                        'plugin' => 'pathauto',
                        'options' => array(
                            'tokenize' => '{subject}/{type}/{title}'
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider',
                        'options' => array(
                            'fields' => array(
                                'title' => function (EntityServiceInterface $es)
                                {
                                    return $es->repository()
                                        ->getCurrentRevision()
                                        ->get('title');
                                },
                                'content' => function (EntityServiceInterface $es)
                                {
                                    return $es->repository()
                                        ->getCurrentRevision()
                                        ->get('content');
                                }
                            )
                        )
                    )
                )
            ),
            'module' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\ModuleForm',
                            'fields' => array(
                                'title'
                            )
                        )
                    ),
                    'taxonomy' => array(
                        'plugin' => 'taxonomy'
                    ),
                    'pages' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'module-page' => array(
                                    'inversed_by' => 'module'
                                )
                            ),
                            'type' => 'link',
                            'association' => 'one-to-many'
                        )
                    ),
                    'page' => array(
                        'plugin' => 'page',
                        'options' => array(
                            'template' => 'learning-resource/plugin/page/module',
                            'fields' => array(
                                'title',
                                'pages'
                            )
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider',
                        'options' => array(
                            'fields' => array(
                                'title' => function (EntityServiceInterface $es)
                                {
                                    return $es->repository()
                                        ->getCurrentRevision()
                                        ->get('title');
                                },
                                'pages' => function (EntityServiceInterface $es)
                                {
                                    return $es->pages()->findChildren();
                                }
                            )
                        )
                    )
                )
            ),
            'module-page' => array(
                'plugins' => array(
                    'repository' => array(
                        'plugin' => 'repository',
                        'options' => array(
                            'revision_form' => 'LearningResource\Form\ModulePageForm',
                            'fields' => array(
                                'title',
                                'content'
                            )
                        )
                    ),
                    'module' => array(
                        'plugin' => 'link',
                        'options' => array(
                            'types' => array(
                                'module' => array(
                                    'inversed_by' => 'pages'
                                )
                            ),
                            'type' => 'link',
                            'association' => 'many-to-one'
                        )
                    ),
                    'provider' => array(
                        'plugin' => 'provider',
                        'options' => array(
                            'fields' => array(
                                'title' => function (EntityServiceInterface $es)
                                {
                                    return $es->repository()
                                        ->getCurrentRevision()
                                        ->get('title');
                                },
                                'content' => function (EntityServiceInterface $es)
                                {
                                    return $es->repository()
                                        ->getCurrentRevision()
                                        ->get('content');
                                }
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
            'LearningResource\Plugin\Page\Controller\PageController',
            'LearningResource\Plugin\Taxonomy\Controller\TaxonomyController'
        ),
        'definition' => array(
            'class' => array(
                'LearningResource\Plugin\Pathauto\Provider\TokenProvider' => array(
                    'setServiceLocator' => array(
                        'required' => true
                    )
                ),
                'LearningResource\Plugin\Taxonomy\Controller\TaxonomyController' => array(
                    'setEntityManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    )
                ),
                'LearningResource\Plugin\Repository\Controller\RepositoryController' => array(
                    'setEntityManager' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                ),
                'LearningResource\Plugin\Page\Controller\PageController' => array(
                    'setAliasManager' => array(
                        'required' => true
                    ),
                    'setEntityManager' => array(
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
                            'page' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/view/:entity',
                                    'defaults' => array(
                                        'controller' => 'LearningResource\Plugin\Page\Controller\PageController',
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
                                                'controller' => 'LearningResource\Plugin\Taxonomy\Controller\TaxonomyController',
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
                    'controller' => 'LearningResource\Plugin\Taxonomy\Controller\TaxonomyController',
                    'actions' => 'update',
                    'roles' => 'moderator'
                )
            )
        )
    )
);