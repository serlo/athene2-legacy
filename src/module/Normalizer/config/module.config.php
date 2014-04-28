<?php

/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Normalizer;

use Normalizer\View\Helper\Normalize;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'view_helpers' => [
        'factories' => [
            'normalize' => function (ServiceLocatorInterface $serviceLocator) {
                    $normalize  = new Normalize();
                    $normalizer = $serviceLocator->getServiceLocator()->get('Normalizer\Normalizer');
                    $normalize->setNormalizer(
                        $normalizer
                    );

                    return $normalize;
                }
        ]
    ],
    'normalizer'   => [
        'strategies' => [
            __NAMESPACE__ . '\Strategy\AttachmentStrategy'     => [],
            __NAMESPACE__ . '\Strategy\CommentStrategy'        => [],
            __NAMESPACE__ . '\Strategy\EntityRevisionStrategy' => [],
            __NAMESPACE__ . '\Strategy\EntityStrategy'         => [],
            __NAMESPACE__ . '\Strategy\PageRepositoryStrategy' => [],
            __NAMESPACE__ . '\Strategy\PageRevisionStrategy'   => [],
            __NAMESPACE__ . '\Strategy\PostStrategy'           => [],
            __NAMESPACE__ . '\Strategy\TaxonomyTermStrategy'   => [],
            __NAMESPACE__ . '\Strategy\UserStrategy'           => [],
        ]
    ],
    'di'           => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\SignpostController',
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Controller\SignpostController' => [
                    'setNormalizer'  => [
                        'required' => true
                    ],
                    'setUuidManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Normalizer'                    => [
                    'setServiceLocator' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\NormalizerInterface' => __NAMESPACE__ . '\Normalizer'
            ]
        ]
    ],
    'router'       => [
        'routes' => [
            'normalizer' => [
                'type'         => 'Zend\Mvc\Router\Http\Segment',
                'options'      => [
                    'route' => ''
                ],
                'child_routes' => [
                    'signpost' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/ref/:object',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\SignpostController',
                                'action'     => 'ref'
                            ]
                        ]
                    ]
                ]
            ],
            'uuid'       => [
                'child_routes' => [
                    'get' => [
                        'type'     => 'Zend\Mvc\Router\Http\Segment',
                        'priority' => -9000,
                        'options'  => [
                            'route'       => '/:uuid',
                            'defaults'    => [
                                'controller' => __NAMESPACE__ . '\Controller\SignpostController',
                                'action'     => 'index'
                            ],
                            'constraints' => [
                                'uuid' => '[0-9]+'
                            ],
                        ]
                    ]
                ]
            ]
        ]
    ]
];
