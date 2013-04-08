<?php
namespace Page;

return array(
    'di' => array(
        'allowed_controllers' => array(
            'Page\Controller\IndexController',
            'Page\Controller\PageController'
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
                    'setLanguageService' => array(
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
                ),
                'Page\Controller\PageController' => array(
                    'setPageService' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Versioning\RepositoryManagerInterface' => 'Versioning\RepositoryManager',
                'Page\Service\PageServiceInterface' => 'Page\Service\PageService',
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService'
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
            'pageShow' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/show/:slug',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'index'
                    )
                )
            ),
            'pageCreate' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/page/create',
                    'defaults' => array(
                        'controller' => 'Page\Controller\PageController',
                        'action' => 'create'
                    )
                )
            ),
            'pageUpdate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/update/:id',
                    'defaults' => array(
                        'controller' => 'Page\Controller\PageController',
                        'action' => 'update'
                    )
                )
            ),
            'pageAdministrate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/administrate/:id',
                    'defaults' => array(
                        'controller' => 'Page\Controller\PageController',
                        'action' => 'administrate'
                    )
                )
            ),
            'pageDelete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/delete/:id',
                    'defaults' => array(
                        'controller' => 'Page\Controller\PageController',
                        'action' => 'delete'
                    )
                )
            )
        )
    ),
    'assetic_configuration' => array(
        // 'routes' => array(),
        
        'default' => array(
            'assets' => array(
                '@page_css',
            ),
            'options' => array(
                'mixin' => true
            )
        ),
        
        'modules' => array(
            'page' => array(
                
                // module root path for yout css and js files
                'root_path' => __DIR__ . '/../assets',
                
                // collection od assets
                'collections' => array(
                    
                    'page_css' => array(
                        'assets' => array(
                            'css/page.css',
                        ),
                        'filters' => array(
                            'CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                        'options' => array()
                    ),
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array()
        // moved to di
        // 'Page\Controller\Index' => 'Page\Controller\IndexController',
        //'Page\Controller\Page' => 'Page\Controller\PageController'
        // 'Page\Controller\Page' => 'Page\Controller\PageController'
    ),
    'service_manager' => array(
        'factories' => array()
        //'Page\Service\PageService' => 'Page\Service\PageService'
        // 'Page\Service\PageService' => 'Page\Service\PageService'
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