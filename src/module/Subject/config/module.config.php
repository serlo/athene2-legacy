<?php
namespace Math;

return array(
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Mathe',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Startseite',
                        'route' => 'math'
                    ),
                    'dynamic' => array(
                        'provider' => 'Navigation\Provider\TaxonomyProvider',
                        'options' => array(
                            'name' => 'math:topic',
                        )
                    )
                    /*
                    array(
                        'label' => 'Thema',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Thema1',
                                'route' => 'register'
                            ),
                            array(
                                'label' => 'Thema2',
                                'route' => 'register'
                            )
                        )
                    ),*/
                )
            )
        )
    ),
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
            'math' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/math[/[home]]',
                    'defaults' => array(
                        'controller' => 'Math\Controller\IndexController',
                        'action' => 'index'
                    )
                )
            )
        )
    )
);

