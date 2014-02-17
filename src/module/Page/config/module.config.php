<?php
namespace Page;

return array(
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
    'router'         => array(
        'routes' => array(
            'pages' => array(
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options'       => array(
                    'route'    => '/pages',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action'     => 'index'
                    )
                ),
            ),
            'page'  => array(
                'type'         => 'Zend\Mvc\Router\Http\Segment',
                'options'      => array(
                    'route'    => '/page',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController'
                    )
                ),
                'child_routes' => array(
                    'create'   => array(
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/create',
                            'defaults' => array(
                                'action' => 'create'
                            )
                        )
                    ),
                    'update'   => array(
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/update/:page',
                            'defaults' => array(
                                'action' => 'update'
                            )
                        )
                    ),
                    'view'     => array(
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options'       => array(
                            'route'    => '/view/:page',
                            'defaults' => array(
                                'action' => 'view'
                            )
                        ),
                    ),
                    'revision' => array(
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => array(
                            'route' => '/revision',
                        ),
                        'child_routes' => array(
                            'view'     => array(
                                'type'          => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options'       => array(
                                    'route'    => '/:revision',
                                    'defaults' => array(
                                        'action' => 'viewRevision'
                                    )
                                ),
                            ),
                            'checkout' => array(
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/:page/checkout/:revision',
                                    'defaults' => array(
                                        'action' => 'checkout'
                                    )
                                )
                            ),
                            'view-all' => array(
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/revisions/:page',
                                    'defaults' => array(
                                        'action' => 'viewRevisions'
                                    )
                                )
                            ),
                            'create'   => array(
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/create/:page[/:revision]',
                                    'defaults' => array(
                                        'action' => 'createRevision'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    ),
    'class_resolver' => array(
        'Page\Entity\PageRepositoryInterface' => 'Page\Entity\PageRepository',
        'Page\Entity\PageRevisionInterface'   => 'Page\Entity\PageRevision',
        'Page\Entity\PageInterface'           => 'Page\Entity\Page'
    ),
    'zfc_rbac'       => array(

        'guards' => array(
            'Authorization\Guard\HydratableControllerGuard' => array(
                array(
                    'controller'    => 'Page\Controller\IndexController',
                    'actions'       => array(
                        'editRepository',
                        'createRevision',
                        'trashRevision',
                        'deleteRevision',
                        'deleteRepository',
                        'showRevisions'
                    ),
                    'role_provider' => 'Page\Provider\FirewallHydrator'
                )
            )
        )
    ),
    'di'             => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\IndexController'
        ),
        'definition'          => array(
            'class' => array(

                'Page\Controller\IndexController' => array(
                    'setAliasManager'    => array(
                        'required' => true
                    ),
                    'setObjectManager'   => array(
                        'required' => true
                    ),
                    'setInstanceManager' => array(
                        'required' => true
                    ),
                    'setPageManager'     => array(
                        'required' => true
                    ),
                    'setUserManager'     => array(
                        'required' => true
                    ),
                    'setEventManager'    => array(
                        'required' => true
                    )
                ),
                'Page\Manager\PageManager'        => array(
                    'setRepositoryManager' => array(
                        'required' => true
                    ),
                    'setRoleService'       => array(
                        'required' => true
                    ),
                    'setInstanceManager'   => array(
                        'required' => true
                    ),
                    'setUuidManager'       => array(
                        'required' => true
                    ),
                    'setObjectManager'     => array(
                        'required' => true
                    ),
                    'setClassResolver'     => array(
                        'required' => true
                    ),
                    'setServiceLocator'    => array(
                        'required' => true
                    ),
                    'setUserManager'       => array(
                        'required' => true
                    ),
                    'setLicenseManager'    => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance'            => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\PageManagerInterface' => __NAMESPACE__ . '\Manager\PageManager'
            )
        )
    ),
    'doctrine'       => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default'             => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);


