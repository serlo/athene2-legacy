<?php
namespace Taxonomy;

return array(
    'di' => array(
        'definition' => array(
            'class' => array(
            )
        ),
        'instance' => array(
            'alias' => array(
                'ServiceManager' => 'Zend\ServiceManager\ServiceManager'
            )
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            //'Core\Service\LanguageService' => 'Core\Service\LanguageService',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);