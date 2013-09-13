<?php
namespace Navigation;

return array(
    'service_manager' => array(
        'factories' => array(
            'Navigation\Service\DynamicNavigationFactory' => 'Navigation\Service\DynamicNavigationFactory'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    )
);


