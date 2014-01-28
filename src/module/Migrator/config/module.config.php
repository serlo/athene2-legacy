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
            __NAMESPACE__ . '\Controller\Worker',
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Controller\Worker' => [],
                __NAMESPACE__ . '\Controller\PreConverterChain' => [],
                __NAMESPACE__ . '\Migrator'          => [],
                'Migrator\Worker\ArticleWorker'      => []
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\NormalizerInterface' => __NAMESPACE__ . '\Normalizer'
            ]
        ]
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
    ],
    'console' => array(
        'router' => array(
            'routes' => array(
                'migrator' => [
                    //'type'    => 'Zend\Mvc\Router\Http\Segment',
                    'options' => [
                        'route'    => 'migrate',
                        'defaults' => [
                            'controller' => __NAMESPACE__ . '\Controller\Worker',
                            'action'     => 'index'
                        ]
                    ]
                ]
            )
        )
    ),
    'router' => [
        'routes' => [
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
                }
        ),
    ),
];
