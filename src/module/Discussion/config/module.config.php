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
            'comment' => 'discussion/%d'
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
                $plugin->setConfig($pluginManager->getServiceLocator()
                    ->get('config')['discussion']['filters']);
                $plugin->setLanguageManager($languageManager);
                $plugin->setSharedTaxonomyManager($sharedTaxonomyManager);
                return $plugin;
            }
        )
    ),
    'discussion' => array(
        'filters' => array(
            'taxonomy' => 'Discussion\Filter\TaxonomyFilter'
        )
    ),
    'taxonomy' => array(
        'associations' => array(
            'comments' => function (ServiceLocatorInterface $sm, $collection)
            {
                return new CommentCollection($collection, $sm->get('Discussion\DiscussionManager'));
            }
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
    'navigation' => array(
        'default' => array(
            'community' => array(
                'label' => 'Community',
                'route' => 'discussion',
                'params' => array(),
                'pages' => array(
                    array(
                        'label' => 'Diskussionen',
                        'route' => 'discussion/discussions',
                        'icon' => 'comment'
                    )
                )
            )
        )
    ),
    'class_resolver' => array(
        'Discussion\Entity\CommentInterface' => 'Discussion\Entity\Comment',
        'Discussion\Entity\VoteInterface' => 'Discussion\Entity\Vote',
        'Discussion\Service\CommentServiceInterface' => 'Discussion\Service\CommentService'
    ),
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Controller' => array(
                array(
                    'controller' => 'Discussion\Controller\DiscussionController',
                    'actions' => array(
                        'start',
                        'comment',
                        'vote'
                    ),
                    'roles' => 'login'
                ),
                array(
                    'controller' => 'Discussion\Controller\DiscussionController',
                    'actions' => array(
                        'archive',
                        'trash'
                    ),
                    'roles' => 'moderator'
                )
            )
        )
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
                    'setSharedTaxonomyManager' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                ),
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
                ),
                'Discussion\Service\CommentService' => array(
                    'setObjectManager' => array(
                        'required' => true
                    )
                ),
                'Discussion\Filter\PluginManager' => array()
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Discussion\DiscussionManagerInterface' => 'Discussion\DiscussionManager'
            ),
            'Discussion\Service\CommentService' => array(
                'shared' => false
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Discussion\DiscussionManager' => function ($sm)
            {
                $config = $sm->get('config');
                $class = new DiscussionManager();
                
                $class->setConfig($config['discussion']);
                $class->setServiceLocator($sm->get('ServiceManager'));
                $class->setUuidManager($sm->get('Uuid\Manager\UuidManager'));
                $class->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                $class->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                $class->setSharedTaxonomyManager($sm->get('Taxonomy\Manager\SharedTaxonomyManager'));
                
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

