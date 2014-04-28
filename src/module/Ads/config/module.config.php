<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Jakob Pfab (jakob.pfab@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Ads;

return [
    'router' => [
        'routes' => [
            'ads' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => [
                    'route' => '/horizon',
                    'defaults' => [
                        'controller' => 'Ads\Controller\AdsController',
                        'action' => 'index'
                    ]
                ],
                'child_routes' => [
                    'about' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/about',
                            'defaults' => [
                                'controller' => 'Ads\Controller\AdsController',
                                'action' => 'adPage'
                            ]
                        ],
                        'child_routes' => [
                            'editabout' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options' => [
                                    'route' => '/editabout',
                                    'defaults' => [
                                        'controller' => 'Ads\Controller\AdsController',
                                        'action' => 'editAdPage'
                                    ]
                                ]
                            ],
                            'setabout' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options' => [
                                    'route' => '/setabout',
                                    'defaults' => [
                                        'controller' => 'Ads\Controller\AdsController',
                                        'action' => 'setAbout'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'add' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'controller' => 'Ads\Controller\AdsController',
                                'action' => 'add'
                            ]
                        ]
                    ],
                    'ad' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/:id',
                            'defaults' => [
                                'controller' => 'Ads\Controller\AdsController',
                                'action' => 'add'
                            ]
                        ],
                        'child_routes' => [
                            'delete' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route' => '/delete',
                                    'defaults' => [
                                        'controller' => 'Ads\Controller\AdsController',
                                        'action' => 'delete'
                                    ]
                                ]
                            ],
                            'out' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options' => [
                                    'route' => '/out',
                                    'defaults' => [
                                        'controller' => 'Ads\Controller\AdsController',
                                        'action' => 'out'
                                    ]
                                ]
                            ],
                            'edit' => [
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route' => '/edit',
                                    'defaults' => [
                                        'controller' => 'Ads\Controller\AdsController',
                                        'action' => 'edit'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'zfc_rbac' => [
        'assertion_map' => [
            'ad.create' => 'Authorization\Assertion\RequestInstanceAssertion',
            'ad.update' => 'Authorization\Assertion\InstanceAssertion',
            'ad.get' => 'Authorization\Assertion\InstanceAssertion',
            'ad.remove' => 'Authorization\Assertion\InstanceAssertion'
        ]
    ],
    'view_helpers' => [
        'factories' => [
            'horizon' => __NAMESPACE__ . '\Factory\HorizonHelperFactory'
        ]
    ],
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Manager\AdsManager' => __NAMESPACE__ . '\Factory\AdsManagerFactory'
        ]
    ],
    'class_resolver' => [
        'Ads\Entity\AdInterface' => 'Ads\Entity\Ad',
        'Ads\Entity\AdPageInterface' => 'Ads\Entity\AdPage'
    ],
    'di' => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\AdsController'
        ],
        'definition' => [
            'class' => [
                'Ads\Controller\AdsController' => [
                    'setObjectManager' => [
                        'required' => 'true'
                    ],
                    'setInstanceManager' => [
                        'required' => 'true'
                    ],
                    'setUserManager' => [
                        'required' => 'true'
                    ],
                    'setAdsManager' => [
                        'required' => true
                    ],
                    'setAttachmentManager' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\AdsManagerInterface' => __NAMESPACE__ . '\Manager\AdsManager'
            ]
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];
