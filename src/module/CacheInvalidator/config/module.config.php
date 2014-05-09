<?php

/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Jakob Pfab (jakob.pfab@serlo.org]
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace CacheInvalidator;

return [
    'service_manager'   => [
        'factories' => [
            __NAMESPACE__ . '\Listener\CacheListener'         => __NAMESPACE__ . '\Factory\CacheListenerFactory',
            __NAMESPACE__ . '\Invalidator\InvalidatorManager' => __NAMESPACE__ . '\Factory\InvalidatorManagerFactory',
            __NAMESPACE__ . '\Options\CacheOptions'           => __NAMESPACE__ . '\Factory\CacheOptionsFactory'
        ]
    ],
    'cache_invalidator' => [
        'invalidators' => [
            'factories' => [
                __NAMESPACE__ . '\Invalidator\NavigationStorageInvalidator' => __NAMESPACE__ . '\Factory\NavigationStorageInvalidatorFactory',
                __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'    => __NAMESPACE__ . '\Factory\StrokerStorageInvalidatorFactory'
            ]
        ],
        'listens'      => [
            'Versioning\RepositoryManager'         => [
                'checkout' => [
                    __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'
                ]
            ],
            'Taxonomy\Manager\TaxonomyManager'     => [
                'create' => [
                    __NAMESPACE__ . '\Invalidator\NavigationStorageInvalidator',
                    __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'
                ],
                'update' => [
                    __NAMESPACE__ . '\Invalidator\NavigationStorageInvalidator',
                    __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'
                ]
            ],
            'Navigation\Manager\NavigationManager' => [
                'page.create'      => [
                    __NAMESPACE__ . '\Invalidator\NavigationStorageInvalidator',
                    __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'
                ],
                'page.update'      => [
                    __NAMESPACE__ . '\Invalidator\NavigationStorageInvalidator',
                    __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'
                ],
                'page.remove'      => [
                    __NAMESPACE__ . '\Invalidator\NavigationStorageInvalidator',
                    __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'
                ],
                'parameter.create' => [
                    __NAMESPACE__ . '\Invalidator\NavigationStorageInvalidator',
                    __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'
                ],
                'parameter.update' => [
                    __NAMESPACE__ . '\Invalidator\NavigationStorageInvalidator',
                    __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'
                ],
                'parameter.remove' => [
                    __NAMESPACE__ . '\Invalidator\NavigationStorageInvalidator',
                    __NAMESPACE__ . '\Invalidator\StrokerStorageInvalidator'
                ],
            ]
        ]
    ]
];
