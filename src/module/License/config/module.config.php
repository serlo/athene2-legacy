<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL]
 */
namespace License;

use Zend\ServiceManager\ServiceLocatorInterface;
use License\Manager\LicenseManager;
return [
    'license_manager' => [
        'defaults' => []
    ],
    'service_manager' => [
        'factories' => []
    ],
    'class_resolver' => [
        __NAMESPACE__ . '\Entity\LicenseInterface' => __NAMESPACE__ . '\Entity\License'
    ],
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Manager\LicenseManager' => function (ServiceLocatorInterface $serviceLocator)
            {
                $instance = new LicenseManager();
                $instance->setServiceLocator($serviceLocator);
                $instance->setObjectManager($serviceLocator->get('EntityManager'));
                $instance->setClassResolver($serviceLocator->get('ClassResolver\ClassResolver'));
                $instance->setLanguageManager($serviceLocator->get('Language\Manager\LanguageManager'));
                return $instance;
            }
        ]
    ],
    'di' => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\LicenseController'
        ],
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Controller\LicenseController' => [
                    'setLicenseManager' => [
                        'required' => 'true'
                    ],
                    'setLanguageManager' => [
                        'required' => 'true'
                    ]
                ],
                __NAMESPACE__ . '\Listener\EntityControllerListener' => [
                    'setLicenseManager' => [
                        'required' => true
                    ]
                ],
            ]
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\LicenseManagerInterface' => __NAMESPACE__ . '\Manager\LicenseManager'
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
        ],
        'entity_resolver' => [
            'orm_default' => [
                'resolvers' => [
                    __NAMESPACE__ . '\Entity\LicenseInterface' => __NAMESPACE__ . '\Entity\License'
                ]
            ]
        ]
    ],
    'router' => [
        'routes' => [
            'license' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/license',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\LicenseController'
                    ]
                ],
                'child_routes' => [
                    'manage' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/manage',
                            'defaults' => [
                                'action' => 'manage'
                            ]
                        ]
                    ],
                    'add' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'action' => 'add'
                            ]
                        ]
                    ],
                    'detail' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/detail/:id',
                            'defaults' => [
                                'action' => 'detail'
                            ]
                        ]
                    ],
                    'update' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/update/:id',
                            'defaults' => [
                                'action' => 'update'
                            ]
                        ]
                    ],
                    'remove' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/remove/:id',
                            'defaults' => [
                                'action' => 'remove'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];