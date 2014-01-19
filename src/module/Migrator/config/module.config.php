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
namespace Migrator;

return [
    'di'              => [
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
    'router'          => [
        'routes' => [
            'migrator' => [
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route'    => '/migrate',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\Worker',
                        'action'     => 'index'
                    ]
                ]
            ]
        ]
    ],
    'service_manager' => array(
        'factories' => array(
            'Zend\Cache\Storage\Filesystem' => function ($sm) {
                    $cache = \Zend\Cache\StorageFactory::factory(
                        array(
                            'adapter' => 'filesystem',
                            'plugins' => array(
                                'exception_handler' => array('throw_exceptions' => false),
                                'serializer'
                            )
                        )
                    );

                    $cache->setOptions(
                        array(
                            'cache_dir' => __DIR__ . '../../../data/migrator'
                        )
                    );

                    return $cache;
                },
        ),
    ),
];
