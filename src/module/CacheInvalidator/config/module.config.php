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
            __NAMESPACE__ . '\Listener\CacheListener' => __NAMESPACE__ . '\Factory\CacheListenerFactory',
            __NAMESPACE__ . '\Options\CacheOptions'   => __NAMESPACE__ . '\Factory\CacheOptionsFactory'
        ]
    ],
    'cache_invalidator' => [
        'listens' => [
            'Taxonomy\Manager\TaxonomyManager'     => [
                'create' => [
                    'Navigation\Storage\Storage'
                ],
                'update' => [
                    'Navigation\Storage\Storage'
                ]
            ],
            'Navigation\Manager\NavigationManager' => [
                'page.create'      => [
                    'Navigation\Storage\Storage'
                ],
                'page.update'      => [
                    'Navigation\Storage\Storage'
                ],
                'page.remove'      => [
                    'Navigation\Storage\Storage'
                ],
                'parameter.create' => [
                    'Navigation\Storage\Storage'
                ],
                'parameter.update' => [
                    'Navigation\Storage\Storage'
                ],
                'parameter.remove' => [
                    'Navigation\Storage\Storage'
                ],
            ]
        ]
    ]
];
