<?php
namespace Core;

return array(
    'service_manager' => array(
        'invokables' => array(
            'Core\Service\LanguageService' => 'Core\Service\LanguageService',
            'Core\Service\SubjectService' => 'Core\Service\SubjectService'
        ),
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