<?php
namespace Page;

return array(
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
                    'route' => '/page[/]',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'createRepository'
                    )
                )
            ),
            
            'createrepository' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/createrepository',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'createRepository'
                    )
                )
            ), 
            'setCurrentRevision' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/:slug/:id/setcurrent[/]',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'setCurrentRevision'
                    )
                )
            ),
            'revision' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/:slug/:id[/]',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'showRevision'
                    )
                )
            ),
            'revisions' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/:slug/revisions[/]',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'showRevisions'
                    )
                )
            ),
            'deleterepository' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/:slug/delete[/]',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'deleteRepository'
                    )
                )
            ),
            'editrepository' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/:slug/editrepository[/]',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'editRepository'
                    )
                )
            ),
            'deleterevision' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/:slug/:revisionid/delete[/]',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'deleteRevision'
                    )
                )
            ),
            'createrevision' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/:slug/edit/[:id][/]',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'createRevision'
                    )
                )
            ),
            
            'article' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/page/:slug/',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'article'
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'invokables' => array(),
        
        'factories' => array(
						/*'Page\Service\PageService' => function  ($sm)
						{
							$srv = new \Page\Service\PageService();
							$srv->setObjectManager($sm->get('EntityManager'));
							return $srv;
						}*/
				)
    ),
    'class_resolver' => array(
        'Page\Entity\PageRepositoryInterface' => 'Page\Entity\PageRepository',
        'Page\Entity\PageRevisionInterface' => 'Page\Entity\PageRevision',
        'Page\Entity\PageInterface' => 'Page\Entity\Page',
        'Page\Service\PageServiceInterface' => 'Page\Service\PageService'
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\IndexController'
        ),
        'definition' => array(
            'class' => array(
                'Page\Service\PageService' => array(
                    'setRepositoryManager' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    )
                ),
                'Page\Controller\IndexController' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setPageManager' => array(
                        'required' => 'true'
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                ),
                'Page\Manager\PageManager' => array(
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setUuidManager' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setClassResolver' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\PageManagerInterface' => __NAMESPACE__ . '\Manager\PageManager'
            )
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