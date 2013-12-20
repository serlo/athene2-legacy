<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Entity;

return [
    'taxonomy' => [
        'associations' => [
            'entities' => [
                'options' => [
                    'template' => 'entity/view/minimalistic/default'
                ]
            ]
        ]
    ],
    'class_resolver' => [
        'Entity\Entity\EntityInterface' => 'Entity\Entity\Entity',
        'Entity\Entity\TypeInterface' => 'Entity\Entity\Type'
    ],
    'router' => [
        'routes' => [
            'entity' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/entity',
                    'defaults' => []
                ],
                'child_routes' => [
                    'create' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/create/:type',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action' => 'create'
                            ]
                        ]
                    ],
                    'trash' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/trash/:entity',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action' => 'trash'
                            ]
                        ]
                    ],
                    'restore' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/restore/:entity',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action' => 'restore'
                            ]
                        ]
                    ],
                    'purge' => [
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route' => '/purge/:entity',
                            'defaults' => [
                                'controller' => 'Entity\Controller\EntityController',
                                'action' => 'purge'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'di' => [
        'allowed_controllers' => [
            'Entity\Controller\EntityController'
        ],
        'definition' => [
            'class' => [
                'Entity\Controller\EntityController' => [
                    'setEntityManager' => [
                        'required' => true
                    ],
                    'setLanguageManager' => [
                        'required' => true
                    ],
                    'setUserManager' => [
                        'required' => true
                    ]
                ],
                'Entity\Manager\EntityManager' => [
                    'setUuidManager' => [
                        'required' => true
                    ],
                    'setObjectManager' => [
                        'required' => true
                    ],
                    'setClassResolver' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                'Entity\Manager\EntityManagerInterface' => 'Entity\Manager\EntityManager'
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Options\ModuleOptions' => __NAMESPACE__ . '\Factory\ModuleOptionsFactory'
        ]
    ],
    'view_helpers' => [
        'factories' => [
            'entity' => __NAMESPACE__ . '\Factory\EntityHelperFactory'
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view'
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