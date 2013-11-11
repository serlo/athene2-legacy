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
                'may_terminate' => true,
                'options' => array(
                    'route' => '/page',
                    'defaults' => array(
                        'controller' => 'Page\Controller\IndexController',
                        'action' => 'index'
                    )
                ),
                'child_routes' => array(
                    'createrepository' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/create-repository',
                            'defaults' => array(
                                'controller' => 'Page\Controller\IndexController',
                                'action' => 'createRepository'
                            )
                        )
                    ),
                    'article' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/view/:slug',
                            'defaults' => array(
                                'controller' => 'Page\Controller\IndexController',
                                'action' => 'article'
                            )
                        ),
                        'child_routes' => array(
                            'revision' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/:id',
                                    'defaults' => array(
                                        'controller' => 'Page\Controller\IndexController',
                                        'action' => 'showRevision'
                                    )
                                ),
                                'child_routes' => array(
                                    'setCurrent' => array(
                                        'type' => 'Zend\Mvc\Router\Http\Segment',
                                        'options' => array(
                                            'route' => '/setcurrent',
                                            'defaults' => array(
                                                'controller' => 'Page\Controller\IndexController',
                                                'action' => 'setCurrentRevision'
                                            )
                                        )
                                    )
                                )
                            ),
                            'revisions' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/revisions',
                                    'defaults' => array(
                                        'controller' => 'Page\Controller\IndexController',
                                        'action' => 'showRevisions'
                                    )
                                )
                            ),
                            'deleterepository' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/delete',
                                    'defaults' => array(
                                        'controller' => 'Page\Controller\IndexController',
                                        'action' => 'deleteRepository'
                                    )
                                )
                            ),
                            
                            'editrepository' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/editrepository',
                                    'defaults' => array(
                                        'controller' => 'Page\Controller\IndexController',
                                        'action' => 'editRepository'
                                    )
                                )
                            ),
                            'deleterevision' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/:revisionid/delete',
                                    'defaults' => array(
                                        'controller' => 'Page\Controller\IndexController',
                                        'action' => 'deleteRevision'
                                    )
                                )
                            ),
                            'createrevision' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/edit[/:id]',
                                    'defaults' => array(
                                        'controller' => 'Page\Controller\IndexController',
                                        'action' => 'createRevision'
                                    )
                                )
                            )
                        )
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
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Controller' => array(
                array(
                    'controller' => 'Page\Controller\IndexController',
                    'actions' => array(
                        'createRepository',
                        'showRevisions',
                        'setCurrentRevision',
                        'showRevision',
                        'editRepository',
                        'createRevision',
                        'deleteRevision',
                        'deleteRepository',
                        'index'
                    ),
                    'roles' => 'moderator'
                ),
                array(
                    'controller' => 'Page\Controller\IndexController',
                    'actions' => array(
                        'article'
                    ),
                    'roles' => 'guest'
                )
            )
        )
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
                    ),
                    'setEventManager' => array(
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