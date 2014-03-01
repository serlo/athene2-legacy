<?php
namespace Session;

return [
    'service_manager' => [
        'factories' => [
            'Zend\Session\SaveHandler\SaveHandlerInterface' => __NAMESPACE__ . '\Factory\SaveHandlerFactory'
        ]
    ]
];