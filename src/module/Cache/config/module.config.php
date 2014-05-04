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
namespace Cache;

return [
    'service_manager' => array(
        'factories' => array(
            'StrokerCache\Listener\CacheListener'              => 'StrokerCache\Factory\CacheListenerFactory',
            'StrokerCache\Options\ModuleOptions'               => 'StrokerCache\Factory\ModuleOptionsFactory',
            'strokerCache\Service\CacheService'                => 'StrokerCache\Factory\CacheServiceFactory',
            'StrokerCache\Storage\CacheStorage'                => 'StrokerCache\Factory\CacheStorageFactory',
            'StrokerCache\Strategy\CacheStrategyPluginManager' => 'StrokerCache\Factory\CacheStrategyPluginManagerFactory',
        ),
    ),
    'strokercache' => [
        'id_generator' => 'fulluri'
    ]
];
