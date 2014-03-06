<?php
namespace Page;

return [
    'zfc_rbac'       => [
        'assertion_manager' => [
            'factories' => [
                __NAMESPACE__ . '\Assertion\PageAssertion' => __NAMESPACE__ . '\Factory\PageAssertionFactory',
            ]
        ],
        'assertion_map'     => [
            'page.create'            => 'Authorization\Assertion\RequestInstanceAssertion',
            'page.purge'             => 'Authorization\Assertion\InstanceAssertion',
            'page.restore'           => 'Authorization\Assertion\InstanceAssertion',
            'page.trash'             => 'Authorization\Assertion\InstanceAssertion',
            'page.update'            => 'Authorization\Assertion\InstanceAssertion',
            'page.get'               => 'Authorization\Assertion\InstanceAssertion',
            'page.revision.purge'    => 'Authorization\Assertion\InstanceAssertion',
            'page.revision.checkout' => __NAMESPACE__ . '\Assertion\PageAssertion',
            'page.revision.create'   => __NAMESPACE__ . '\Assertion\PageAssertion',
            'page.revision.restore'  => __NAMESPACE__ . '\Assertion\PageAssertion',
            'page.revision.trash'    => __NAMESPACE__ . '\Assertion\PageAssertion',
        ]
    ],
    'versioning'     => [
        'permissions' => [
            'Page\Entity\PageRepository' => [
                'commit'   => 'page.revision.create',
                'checkout' => 'page.revision.checkout',
                'reject'   => 'page.revision.trash'
            ]
        ]
    ],
    'uuid'           => [
        'permissions' => [
            'Page\Entity\PageRevision'   => [
                'trash'   => 'page.revision.trash',
                'restore' => 'page.revision.restore',
                'purge'   => 'page.revision.purge'
            ],
            'Page\Entity\PageRepository' => [
                'trash'   => 'page.trash',
                'restore' => 'page.restore',
                'purge'   => 'page.purge'
            ]
        ]
    ],
    'router'         => [
        'routes' => [
            'pages' => [
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/pages',
                    'defaults' => [
                        'controller' => 'Page\Controller\IndexController',
                        'action'     => 'index'
                    ]
                ],
            ],
            'page'  => [
                'type'         => 'Zend\Mvc\Router\Http\Segment',
                'options'      => [
                    'route'    => '/page',
                    'defaults' => [
                        'controller' => 'Page\Controller\IndexController'
                    ]
                ],
                'child_routes' => [
                    'create'   => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/create',
                            'defaults' => [
                                'action' => 'create'
                            ]
                        ]
                    ],
                    'update'   => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/update/:page',
                            'defaults' => [
                                'action' => 'update'
                            ]
                        ]
                    ],
                    'view'     => [
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/view/:page',
                            'defaults' => [
                                'action' => 'view'
                            ]
                        ],
                    ],
                    'revision' => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route' => '/revision',
                        ],
                        'child_routes' => [
                            'view'     => [
                                'type'          => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'    => '/:revision',
                                    'defaults' => [
                                        'action' => 'viewRevision'
                                    ]
                                ],
                            ],
                            'checkout' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/:page/checkout/:revision',
                                    'defaults' => [
                                        'action' => 'checkout'
                                    ]
                                ]
                            ],
                            'view-all' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/revisions/:page',
                                    'defaults' => [
                                        'action' => 'viewRevisions'
                                    ]
                                ]
                            ],
                            'create'   => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/create/:page[/:revision]',
                                    'defaults' => [
                                        'action' => 'createRevision'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'class_resolver' => [
        'Page\Entity\PageRepositoryInterface' => 'Page\Entity\PageRepository',
        'Page\Entity\PageRevisionInterface'   => 'Page\Entity\PageRevision',
        'Page\Entity\PageInterface'           => 'Page\Entity\Page'
    ],
    'di'             => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\IndexController'
        ],
        'definition'          => [
            'class' => [
                'Page\Controller\IndexController' => [
                    'setAliasManager'    => [
                        'required' => true
                    ],
                    'setObjectManager'   => [
                        'required' => true
                    ],
                    'setInstanceManager' => [
                        'required' => true
                    ],
                    'setPageManager'     => [
                        'required' => true
                    ],
                    'setUserManager'     => [
                        'required' => true
                    ],
                    'setEventManager'    => [
                        'required' => true
                    ]
                ],
                'Page\Manager\PageManager'        => []
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\PageManagerInterface' => __NAMESPACE__ . '\Manager\PageManager'
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
