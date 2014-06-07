<?php
namespace PageSpeed;

return [
    'service_manager' => [
        'factories'  => [
            'doctrine.cache.apccache' => __NAMESPACE__ . '\Factory\ApcCacheFactory'
        ]
    ]
];
