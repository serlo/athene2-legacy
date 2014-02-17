<?php
namespace Page;

return [
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
    'zfc_rbac'       => [

        'guards' => [
            'Authorization\Guard\HydratableControllerGuard' => [
                [
                    'controller'    => 'Page\Controller\IndexController',
                    'actions'       => [
                        'editRepository',
                        'createRevision',
                        'trashRevision',
                        'deleteRevision',
                        'deleteRepository',
                        'showRevisions'
                    ],
                    'role_provider' => 'Page\Provider\FirewallHydrator'
                ]
            ]
        ]
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
                'Page\Manager\PageManager'        => [
                    'setRepositoryManager' => [
                        'required' => true
                    ],
                    'setRoleService'       => [
                        'required' => true
                    ],
                    'setInstanceManager'   => [
                        'required' => true
                    ],
                    'setUuidManager'       => [
                        'required' => true
                    ],
                    'setObjectManager'     => [
                        'required' => true
                    ],
                    'setClassResolver'     => [
                        'required' => true
                    ],
                    'setServiceLocator'    => [
                        'required' => true
                    ],
                    'setUserManager'       => [
                        'required' => true
                    ],
                    'setLicenseManager'    => [
                        'required' => true
                    ]
                ]
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


