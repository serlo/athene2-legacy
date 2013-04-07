<?php
namespace Core;

return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'ServiceManager' => 'Zend\ServiceManager\ServiceManager'
            )
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'Core\Service\LanguageService' => 'Core\Service\LanguageService',
            'Core\Service\SubjectService' => 'Core\Service\SubjectService'
        )
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'getParam' => 'Core\Controller\Plugin\GetParam',
            'getParams' => 'Core\Controller\Plugin\GetParams',
            'title' => 'Core\Controller\Plugin\Title'
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