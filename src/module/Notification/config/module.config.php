<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Notification;

use Notification\View\Helper\Notification;

return [
    'view_helpers'    => [
        'factories' => [
            'notifications' => function ($sm) {
                    $helper = new Notification();
                    $helper->setUserManager(
                        $sm->getServiceLocator()->get('User\Manager\UserManager')
                    );
                    $helper->setNotificationManager(
                        $sm->getServiceLocator()->get('Notification\NotificationManager')
                    );

                    return $helper;
                },
        ]
    ],
    'router'          => [
        'routes' => [
            'notification' => [
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'options'       => [
                    'route'    => '/notification',
                    'defaults' => [
                        'controller' => 'Notification\Controller\NotificationController',
                    ]
                ],
                'may_terminate' => false,
                'child_routes'  => [
                    'read' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/read',
                            'defaults' => [
                                'action' => 'read'
                            ]
                        ]
                    ],
                ]
            ],
            'subscription' => [
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'options'       => [
                    'route'    => '',
                    'defaults' => [
                        'controller' => 'Notification\Controller\SubscriptionController',
                    ]
                ],
                'may_terminate' => false,
                'child_routes'  => [
                    'subscribe' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/subscribe/:object/:email',
                            'defaults' => [
                                'action' => 'subscribe'
                            ]
                        ]
                    ],
                    'unsubscribe' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/unsubscribe/:object',
                            'defaults' => [
                                'action' => 'unsubscribe'
                            ]
                        ]
                    ],
                ]
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\NotificationManager' => __NAMESPACE__ . '\Factory\NotificationManagerFactory',
        ]
    ],
    'class_resolver'  => [
        __NAMESPACE__ . '\Entity\NotificationEventInterface' => __NAMESPACE__ . '\Entity\NotificationEvent',
        __NAMESPACE__ . '\Entity\NotificationInterface'      => __NAMESPACE__ . '\Entity\Notification',
        __NAMESPACE__ . '\Entity\SubscriptionInterface'      => __NAMESPACE__ . '\Entity\Subscription'
    ],
    'di'              => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\WorkerController',
            __NAMESPACE__ . '\Controller\NotificationController'
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Listener\DiscussionManagerListener' => [
                    'setSubscriptionManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Listener\RepositoryManagerListener' => [
                    'setSubscriptionManager' => [
                        'required' => true
                    ],
                    'setUserManager'         => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\SubscriptionManager'                => [
                    'setClassResolver' => [
                        'required' => true
                    ],
                    'setObjectManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\NotificationWorker'                 => [
                    'setUserManager'         => [
                        'required' => true
                    ],
                    'setObjectManager'       => [
                        'required' => true
                    ],
                    'setSubscriptionManager' => [
                        'required' => true
                    ],
                    'setNotificationManager' => [
                        'required' => true
                    ],
                    'setClassResolver'       => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Controller\WorkerController'        => [
                    'setNotificationWorker' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Controller\NotificationController'  => [],
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\SubscriptionManagerInterface' => __NAMESPACE__ . '\SubscriptionManager',
                __NAMESPACE__ . '\NotificationManagerInterface' => __NAMESPACE__ . '\NotificationManager'
            ]
        ]
    ],
    'console'         => [
        'router' => [
            'routes' => [
                'notification-worker' => [
                    'options' => [
                        'route'    => 'notification worker',
                        'defaults' => [
                            'controller' => __NAMESPACE__ . '\Controller\WorkerController',
                            'action'     => 'run'
                        ]
                    ]
                ],
            ]
        ],
    ],
    'doctrine'        => [
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
