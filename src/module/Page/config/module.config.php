<?php
namespace Page;

return array(
    'di' => array(
        'allowed_controllers' => array(
            'Page\Controller\IndexController'
        ),
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
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setAuthService' => array(
                        'required' => 'true'
                    )
                ),
                'Page\Controller\IndexController' => array(
                    'setPageService' => array(
                        'required' => 'true'
                    )
                )
            ),
            'instance' => array(
                'preferences' => array(
        			'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager',
        			'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
        			'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService',
        		),
        	)
        ),
        'instance' => array(
            'preferences' => array(
                'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager',
                'Page\Service\PageServiceInterface' => 'Page\Service\PageService',
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
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
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page[/[:slug]]',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
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
            // moved to di
            // 'Page\Controller\Index' => 'Page\Controller\IndexController',
            'Page\Controller\Restricted' => 'Page\Controller\RestrictedController'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            //'Page\Service\PageService' => 'Page\Service\PageService'
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