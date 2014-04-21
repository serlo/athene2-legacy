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
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Listener\CacheListener' => __NAMESPACE__ . '\Factory\CacheListenerFactory',
            __NAMESPACE__ . '\Options\CacheOptions' => __NAMESPACE__ . '\Factory\CacheOptionsFactory'
        ]
    ],
    'cache_invalidator' => [
        'listens' => [
            'Taxonomy\Manager\TaxonomyManager' => [ // Die Klasse mit dem EventManager
                'create' => [ // Das Event
                    'Navigation\Storage\Storage',
                    'Navigation\Storage\NavigationHelperStorage' // Der gesamte storage wird resettet
                ],
                'update' => [ // Das Event
                    'Navigation\Storage\Storage',
                    'Navigation\Storage\NavigationHelperStorage' // Der gesamte storage wird resettet
                ]
            ]
        ]
    ]
]

;
