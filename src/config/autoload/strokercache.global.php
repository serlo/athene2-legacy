<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */

return array(
    'strokercache' => array(
        'strategies' => array(
            'enabled' => array(
                'StrokerCache\Strategy\RouteName' => array(
                    'routes' => array(
                        'taxonomy/term/get',
                        'entity/page',
                        'page/view',
                        'sitemap'
                    ),
                ),
            ),
        ),
        'storage_adapter' => [
            'name' => 'Zend\Cache\Storage\Adapter\Filesystem',
            'options' => [
                'cache_dir' => __DIR__ . '/../../data',
                'ttl' => 60*60*24*7
            ]
        ]
    ),
);
