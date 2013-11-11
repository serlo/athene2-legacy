<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Blog;

use Zend\ServiceManager\ServiceLocatorInterface;
use Blog\Collection\PostCollection;
return array(
    'taxonomy' => array(
        'associations' => array(
            'blogPosts' => array(
                'callback' => function (ServiceLocatorInterface $sm, $collection)
                {
                    return new PostCollection($collection, $sm->get('Blog\Manager\BlogManager'));
                }
            )
        ),
        'types' => array(
            'blog' => array(
                'options' => array(
                    'allowed_associations' => array(
                        'blogPosts'
                    ),
                    'allowed_parents' => array(
                        'root'
                    ),
                    'radix_enabled' => false
                )
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
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\BlogController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Controller\BlogController' => array(
                    'setBlogManager' => array(
                        'required' => true
                    ),
                    'setUserManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Manager\BlogManager' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setClassResolver' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Manager\PostManager' => array(
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setUuidManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Service\PostService' => array(
                    'setObjectManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\BlogManagerInterface' => __NAMESPACE__ . '\Manager\BlogManager'
            ),
            __NAMESPACE__ . '\Manager\PostManager' => array(
                'shared' => true
            ),
            __NAMESPACE__ . '\Service\PostService' => array(
                'shared' => false
            )
        )
    ),
    'class_resolver' => array(
        __NAMESPACE__ . '\Entity\PostInterface' => __NAMESPACE__ . '\Entity\Post',
        __NAMESPACE__ . '\Service\PostServiceInterface' => __NAMESPACE__ . '\Service\PostService',
        __NAMESPACE__ . '\Manager\PostManagerInterface' => __NAMESPACE__ . '\Manager\PostManager'
    ),
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/blog',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\BlogController',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'view-all' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/view-all/:id',
                            'defaults' => array(
                                'action' => 'viewAll'
                            )
                        )
                    ),
                    'view' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/view/:id',
                            'defaults' => array(
                                'action' => 'view'
                            )
                        )
                    ),
                    'post' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/post',
                            'defaults' => array(
                                'action' => 'view'
                            )
                        ),
                        'child_routes' => array(
                            'create' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/create/:id',
                                    'defaults' => array(
                                        'action' => 'create'
                                    )
                                )
                            ),
                            'view' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/view/:blog/:post',
                                    'defaults' => array(
                                        'action' => 'viewPost'
                                    )
                                )
                            ),
                            'update' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/update/:blog/:post',
                                    'defaults' => array(
                                        'action' => 'update'
                                    )
                                )
                            ),
                            'trash' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route' => '/trash/:blog/:post',
                                    'defaults' => array(
                                        'action' => 'trash'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    ),
);