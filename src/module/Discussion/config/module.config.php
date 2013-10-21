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
return array(
    'uuid_router' => array(
        'routes' => array(
            'comment' => 'discussion/%d'
        )
    ),
    'discussion' => array(),
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
                        'comment',
                        'vote'
                    ),
                    'roles' => 'login'
                ),
                array(
                    'controller' => 'Discussion\Controller\DiscussionController',
                    'actions' => array(
                        'archive',
                        'trash',
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
                                'vote' => 'up|down',
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
                            ),
                        )
                    ),
                    'archive' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/archive/:comment',
                            'defaults' => array(
                                'controller' => 'Discussion\Controller\DiscussionController',
                                'action' => 'archive'
                            ),
                        )
                    )
                )
            )
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Discussion\Controller\DiscussionController'
        ),
        'definition' => array(
            'class' => array(
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
                )
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
            'Discussion\DiscussionManager' => (function ($sm)
            {
                $config = $sm->get('config');
                $class = new DiscussionManager();
                
                $class->setConfig($config['discussion']);
                $class->setServiceLocator($sm->get('ServiceManager'));
                $class->setUuidManager($sm->get('Uuid\Manager\UuidManager'));
                $class->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                $class->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                
                return $class;
            })
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