<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Notification;

use Notification\View\Helper\Notification;

return array(
    'view_helpers'   => array(
        'factories' => array(
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
        )
    ),
    'class_resolver' => array(
        __NAMESPACE__ . '\Entity\NotificationEventInterface' => __NAMESPACE__ . '\Entity\NotificationEvent',
        __NAMESPACE__ . '\Entity\NotificationInterface'      => __NAMESPACE__ . '\Entity\Notification',
        __NAMESPACE__ . '\Entity\SubscriptionInterface'      => __NAMESPACE__ . '\Entity\Subscription'
    ),
    'di'             => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\WorkerController'
        ),
        'definition'          => array(
            'class' => array(
                __NAMESPACE__ . '\Listener\DiscussionManagerListener' => array(
                    'setSubscriptionManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Listener\RepositoryManagerListener'    => array(
                    'setSubscriptionManager' => array(
                        'required' => true
                    ),
                    'setUserManager'         => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\SubscriptionManager'                   => array(
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\NotificationManager'                   => array(
                    'setClassResolver'  => array(
                        'required' => true
                    ),
                    'setObjectManager'  => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\NotificationWorker'                    => array(
                    'setUserManager'         => array(
                        'required' => true
                    ),
                    'setObjectManager'       => array(
                        'required' => true
                    ),
                    'setSubscriptionManager' => array(
                        'required' => true
                    ),
                    'setNotificationManager' => array(
                        'required' => true
                    ),
                    'setClassResolver'       => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\WorkerController'           => array(
                    'setNotificationWorker' => array(
                        'required' => true
                    )
                ),
            )
        ),
        'instance'            => array(
            'preferences' => array(
                __NAMESPACE__ . '\SubscriptionManagerInterface' => __NAMESPACE__ . '\SubscriptionManager',
                __NAMESPACE__ . '\NotificationManagerInterface' => __NAMESPACE__ . '\NotificationManager'
            )
        )
    ),
    'console'        => array(
        'router' => array(
            'routes' => array(
                'notification-worker' => array(
                    'options' => array(
                        'route'    => 'notification worker',
                        'defaults' => array(
                            'controller' => __NAMESPACE__ . '\Controller\WorkerController',
                            'action'     => 'run'
                        )
                    )
                ),
            )
        ),
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
