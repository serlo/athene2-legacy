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
                    $normalize = new Normalize();
                    $normalize->setNormalizer(
                        $serviceLocator->getServiceLocator()->get('Normalizer\Normalizer')
                    );

                    return $normalize;
                }
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
                    'taxonomy' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/signpost/:object',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\SignpostController',
                                'action'     => 'index'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];