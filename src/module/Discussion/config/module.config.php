<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Discussion;

return [
    'zfc_rbac'       => [
        'assertion_map' => [
            'discussion.trash'         => 'Authorization\Assertion\LanguageAssertion',
            'discussion.purge'         => 'Authorization\Assertion\LanguageAssertion',
            'discussion.vote'          => 'Authorization\Assertion\LanguageAssertion',
            'discussion.archive'       => 'Authorization\Assertion\LanguageAssertion',
            'discussion.comment.trash' => 'Authorization\Assertion\LanguageAssertion',
            'discussion.comment.purge' => 'Authorization\Assertion\LanguageAssertion',
        ]
    ],
    'uuid_router'    => [
        'routes' => [
            'comment' => '/discussion/%d'
        ]
    ],
    'term_router'    => [
        'routes' => [
            'forum'          => [
                'route'          => 'discussion/discussions',
                'param_provider' => 'Discussion\Provider\ParamProvider'
            ],
            'forum-category' => [
                'route'          => 'discussion/discussions',
                'param_provider' => 'Discussion\Provider\ParamProvider'
            ]
        ]
    ],
    'view_helpers'   => [
        'factories' => [
            'discussion' => __NAMESPACE__ . '\Factory\DiscussionHelperFactory'
        ]
    ],
    'taxonomy'       => [
        'types' => [
            'forum-category' => [
                'allowed_parents' => [
                    'subject',
                    'root'
                ],
                'rootable'        => false
            ],
            'forum'          => [
                'allowed_associations' => [
                    'comments'
                ],
                'allowed_parents'      => [
                    'forum',
                    'forum-category'
                ],
                'rootable'             => false
            ]
        ]
    ],
    'class_resolver' => [
        'Discussion\Entity\CommentInterface' => 'Discussion\Entity\Comment',
        'Discussion\Entity\VoteInterface'    => 'Discussion\Entity\Vote'
    ],
    'router'         => [
        'routes' => [
            'discussion' => [
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'options'       => [
                    'route' => ''
                ],
                'may_terminate' => false,
                'child_routes'  => [
                    'view'        => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/discussion/:id',
                            'defaults' => [
                                'controller' => 'Discussion\Controller\DiscussionController',
                                'action'     => 'view'
                            ]
                        ]
                    ],
                    'discussions' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/discussions[/:id]',
                            'defaults' => [
                                'controller' => 'Discussion\Controller\DiscussionsController',
                                'action'     => 'index'
                            ]
                        ]
                    ],
                    'discussion'  => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route'    => '/discussion',
                            'defaults' => []
                        ],
                        'child_routes' => [
                            'start'   => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/start/:on',
                                    'defaults' => [
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action'     => 'start'
                                    ]
                                ]
                            ],
                            'comment' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/comment/:discussion',
                                    'defaults' => [
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action'     => 'comment'
                                    ]
                                ]
                            ],
                            'vote'    => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'       => '/vote/:vote/:comment',
                                    'defaults'    => [
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action'     => 'vote'
                                    ],
                                    'constraints' => [
                                        'vote' => 'up|down'
                                    ]
                                ]
                            ],
                            'trash'   => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/trash/:comment',
                                    'defaults' => [
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action'     => 'trash'
                                    ]
                                ]
                            ],
                            'archive' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/archive/:comment',
                                    'defaults' => [
                                        'controller' => 'Discussion\Controller\DiscussionController',
                                        'action'     => 'archive'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'di'             => [
        'allowed_controllers' => [
            'Discussion\Controller\DiscussionController',
            'Discussion\Controller\DiscussionsController'
        ],
        'definition'          => [
            'class' => [
                'Discussion\Controller\DiscussionsController' => [
                    'setDiscussionManager'     => [
                        'required' => true
                    ],
                    'setDiscussionFilterChain' => [
                        'required' => true
                    ],
                    'setLanguageManager'       => [
                        'required' => true
                    ],
                    'setTaxonomyManager'       => [
                        'required' => true
                    ],
                    'setUserManager'           => [
                        'required' => true
                    ]
                ],
                'Discussion\DiscussionManager'                => [
                    'setObjectManager'        => [
                        'required' => true
                    ],
                    'setUuidManager'          => [
                        'required' => true
                    ],
                    'setClassResolver'        => [
                        'required' => true
                    ],
                    'setTaxonomyManager'      => [
                        'required' => true
                    ],
                    'setAuthorizationService' => [
                        'required' => true
                    ]
                ],
                'Discussion\Controller\DiscussionController'  => [
                    'setDiscussionManager' => [
                        'required' => true
                    ],
                    'setUuidManager'       => [
                        'required' => true
                    ],
                    'setLanguageManager'   => [
                        'required' => true
                    ],
                    'setUserManager'       => [
                        'required' => true
                    ],
                ]
            ]
        ],
        'instance'            => [
            'preferences' => [
                'Discussion\DiscussionManagerInterface' => 'Discussion\DiscussionManager'
            ]
        ]
    ],
    'doctrine'       => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ]
            ],
            'orm_default'             => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];