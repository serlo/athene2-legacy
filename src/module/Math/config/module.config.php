<?php
namespace Math;

return array(
    'di' => array(
        'allowed_controllers' => array(
            'Math\Controller\Exercises\IndexController'
        ),
        'definition' => array(
            'class' => array(
                'Math\Controller\Exercises\IndexController' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Entity\EntityManagerInterface' => 'Entity\EntityManager',
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager'
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Math\Controller\IndexController' => 'Math\Controller\IndexController'
        )
    ),
    'router' => array(
        'routes' => array(
            'mathExerciseShow' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/math/exercises[/[:id]]',
                    'defaults' => array(
                        'controller' => 'Math\Controller\Exercises\IndexController',
                        'action' => 'index'
                    )
                )
            ),
            'mathHome' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/math[/]',
                    'defaults' => array(
                        'controller' => 'Math\Controller\IndexController',
                        'action' => 'index'
                    )
                )
            )
        )
    )
);

