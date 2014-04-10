<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Subject;

return [
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Options\ModuleOptions'  => __NAMESPACE__ . '\Factory\ModuleOptionsFactory',
            __NAMESPACE__ . '\Manager\SubjectManager' => __NAMESPACE__ . '\Factory\SubjectManagerFactory',
            __NAMESPACE__ . '\Hydrator\Navigation'    => __NAMESPACE__ . '\Factory\NavigationFactory'
        ]
    ],
    'view_helpers'    => [
        'factories' => [
            'subject' => __NAMESPACE__ . '\Factory\SubjectHelperFactory'
        ]
    ],
    'taxonomy'        => [
        'types' => [
            'topic-folder'            => [
                'allowed_associations' => [
                    'Entity\Entity\EntityInterface'
                ],
                'allowed_parents'      => [
                    'topic'
                ],
                'rootable'             => false
            ],
            'topic'                   => [
                'allowed_parents' => [
                    'subject',
                    'topic'
                ],
                'allowed_associations' => [
                    'Entity\Entity\EntityInterface'
                ],
                'rootable'        => false
            ],
            'subject'                 => [
                'allowed_parents' => [
                    'root'
                ],
                'rootable'        => false
            ],
            'locale'                  => [
                'allowed_parents' => [
                    'subject',
                    'locale'
                ],
                'rootable'        => false
            ],
            'curriculum'              => [
                'allowed_parents' => [
                    'subject',
                    'locale'
                ],
                'rootable'        => false
            ],
            'curriculum-topic'       => [
                'allowed_associations' => [
                    'Entity\Entity\EntityInterface'
                ],
                'allowed_parents'      => [
                    'curriculum',
                    'curriculum-topic'
                ],
                'rootable'             => false
            ],
            'curriculum-topic-folder' => [
                'allowed_associations' => [
                    'Entity\Entity\EntityInterface'
                ],
                'allowed_parents'      => [
                    'curriculum-topic'
                ],
                'rootable'             => false
            ]
        ]
    ],
    'router'          => [
        'routes' => [
            'subject' => [
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'options'       => [
                    'route'    => '/:subject',
                    'constraints' => [
                        'subject' => '[mathe|physik|chemie|permakultur]+'
                    ]
                ],
                'child_routes'  => [
                    'taxonomy' => [
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'       => '/taxonomy/:id',
                            'defaults'    => [
                                'controller' => __NAMESPACE__ . '\Controller\TaxonomyController',
                                'action'     => 'index'
                            ],
                            'constraints' => [
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    'entity'   => [
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'options'       => [
                            'route'    => '/entity/:action',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\EntityController',
                                'action'     => 'index',
                            ]
                        ]
                    ],
                    'home'   => [
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'options'       => [
                            'route'    => '',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\HomeController',
                                'action'     => 'index',
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'di'              => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\TaxonomyController',
            __NAMESPACE__ . '\Controller\EntityController',
            __NAMESPACE__ . '\Controller\HomeController'
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Controller\HomeController'     => [
                    'setInstanceManager' => [
                        'required' => true
                    ],
                    'setSubjectManager'  => [
                        'required' => true
                    ],
                    'setTaxonomyManager'  => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Controller\TaxonomyController' => [
                    'setInstanceManager' => [
                        'required' => true
                    ],
                    'setSubjectManager'  => [
                        'required' => true
                    ],
                    'setTaxonomyManager'  => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Controller\EntityController'   => [
                    'setInstanceManager' => [
                        'required' => true
                    ],
                    'setSubjectManager'  => [
                        'required' => true
                    ],
                    'setTaxonomyManager'  => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\SubjectManagerInterface' => __NAMESPACE__ . '\Manager\SubjectManager'
            ]
        ]
    ]
];