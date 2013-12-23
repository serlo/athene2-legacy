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
namespace Discussion;

use Discussion\DiscussionManager;
use Discussion\View\Helper\Discussion;
use Discussion\Collection\CommentCollection;
use Zend\ServiceManager\ServiceLocatorInterface;
return array(
    'uuid_router' => array(
        'routes' => array(
            'comment' => '/discussion/%d'
        )
    ),
    'term_router' => array(
        'routes' => array(
            'forum' => array(
                'route' => 'discussion/discussions',
                'param_provider' => 'Discussion\Provider\ParamProvider'
            ),
            'forum-category' => array(
                'route' => 'discussion/discussions',
                'param_provider' => 'Discussion\Provider\ParamProvider'
            )
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'discussion' => function ($pluginManager)
            {
                $plugin = new Discussion();
                $discussionManager = $pluginManager->getServiceLocator()->get('Discussion\DiscussionManager');
                $userManager = $pluginManager->getServiceLocator()->get('User\Manager\UserManager');
                $languageManager = $pluginManager->getServiceLocator()->get('Language\Manager\LanguageManager');
                $sharedTaxonomyManager = $pluginManager->getServiceLocator()->get('Taxonomy\Manager\SharedTaxonomyManager');
                $plugin->setDiscussionManager($discussionManager);
                $plugin->setUserManager($userManager);
                $plugin->setLanguageManager($languageManager);
                $plugin->setTaxonomyManager($sharedTaxonomyManager);
                return $plugin;
            }
        )
    ),
    'taxonomy' => array(
        'associations' => array(
            'comments' => array(
                'callback' => function (ServiceLocatorInterface $sm, $collection)
                {
                    return new $collection();
                }
            )
        ),
        'types' => array(
            'forum-category' => array(
                'options' => array(
                    'allowed_parents' => array(
                        'subject',
                        'root'
                    ),
                    'radix_enabled' => false
                )
            ),
            'forum' => array(
                'options' => array(
                    'allowed_associations' => array(
                        'comments'
                    ),
                    'allowed_parents' => array(
                        'forum',
                        'forum-category'
                    ),
                    'radix_enabled' => false
                )
            )
        )
    ),
    'class_resolver' => array(
        'Discussion\Entity\CommentInterface' => 'Discussion\Entity\Comment',
        'Discussion\Entity\VoteInterface' => 'Discussion\Entity\Vote'
    ),
    'router' => array(
        'routes' => array(
            'discussion' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => ''
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'view' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/discussion/:id',
                            'defaults' => array(
                                'controller' => 'Discussion\Controller\DiscussionController',
                                'action' => 'view'
                            )
                        )
                    ),
                    'discussions' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/discussions[/:id]',
                            'defaults' => array(
                                'controller' => 'Discussion\Controller\DiscussionsController',
                                'action' => 'index'
                            )
                        )
                    ),
                    'discussion' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/discussion',
                            'defaults' => array()
                        ),
                        'child_routes' => array(
                            'start' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/start/:on',
                                    'defaults' => array(
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action' => 'start'
                                    )
                                )
                            ),
                            'comment' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/comment/:discussion',
                                    'defaults' => array(
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action' => 'comment'
                                    )
                                )
                            ),
                            'vote' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/vote/:vote/:comment',
                                    'defaults' => array(
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action' => 'vote'
                                    ),
                                    'constraints' => array(
                                        'vote' => 'up|down'
                                    )
                                )
                            ),
                            'trash' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/trash/:comment',
                                    'defaults' => array(
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action' => 'trash'
                                    )
                                )
                            ),
                            'archive' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/archive/:comment',
                                    'defaults' => array(
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action' => 'archive'
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
            'Discussion\Controller\DiscussionController',
            'Discussion\Controller\DiscussionsController'
        ),
        'definition' => array(
            'class' => array(
                'Discussion\Controller\DiscussionsController' => array(
                    'setDiscussionManager' => array(
                        'required' => true
                    ),
                    'setDiscussionFilterChain' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    ),
                    'setTaxonomyManager' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Provider\ParamProvider' => array(),
                'Discussion\Controller\DiscussionController' => array(
                    'setDiscussionManager' => array(
                        'required' => true
                    ),
                    'setUuidManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Discussion\DiscussionManagerInterface' => 'Discussion\DiscussionManager'
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Discussion\DiscussionManager' => function ($sm)
            {
                $config = $sm->get('config');
                $class = new DiscussionManager();
                $class->setUuidManager($sm->get('Uuid\Manager\UuidManager'));
                $class->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                $class->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                $class->setTaxonomyManager($sm->get('Taxonomy\Manager\SharedTaxonomyManager'));
                
                return $class;
            }
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