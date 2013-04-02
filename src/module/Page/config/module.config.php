<?php
return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'Page\Service\PageService' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setRepositoryManager' => array(
                        'required' => 'true'
                    ),
                    'setLaunguageService' => array(
                        'required' => 'true'
                    )
                )
            ),
            'instance' => array(
                'preferences' => array(
                    'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager',
                ),
            )
        )
    ),
    'acl' => array(
        'Page\Controller\Restricted' => array(
            'guest' => 'deny',
            'sysadmin' => 'allow'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'router' => array(
        'routes' => array(
            'page' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/page/',
                    'defaults' => array(
                        'controller' => 'Page\Controller\Index',
                        'action' => 'index'
                    )
                )
            ),
            'pageRestricted' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/page/restricted/',
                    'defaults' => array(
                        'controller' => 'Page\Controller\Restricted',
                        'action' => 'index'
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Page\Controller\Index' => 'Page\Controller\IndexController',
            'Page\Controller\Restricted' => 'Page\Controller\RestrictedController'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Page\Service\PageService' => 'Page\Service\PageService'
        )
    )
);
