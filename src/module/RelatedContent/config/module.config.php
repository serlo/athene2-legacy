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
namespace RelatedContent;

use RelatedContent\View\Helper\RelatedContentHelper;
use Zend\ServiceManager\ServiceLocatorInterface;

return array(
    'service_manager' => array(
        'factories' => array(
            __NAMESPACE__ . '\Manager\RelatedContentManager' => function (ServiceLocatorInterface $sl)
            {
                $instance = new Manager\RelatedContentManager();
                $instance->setClassResolver($sl->get('ClassResolver\ClassResolver'));
                $instance->setUuidManager($sl->get('Uuid\Manager\UuidManager'));
                $instance->setObjectManager($sl->get('Doctrine\ORM\EntityManager'));
                $instance->setRouter($sl->get('router'));
                return $instance;
            }
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\RelatedContentController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Controller\RelatedContentController' => array(
                    'setRelatedContentManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\RelatedContentManagerInterface' => __NAMESPACE__ . '\Manager\RelatedContentManager',
                'Zend\Mvc\Router\RouteInterface' => 'Router'
            )
        )
    ),
    'class_resolver' => array(
        __NAMESPACE__ . '\Entity\ContainerInterface' => __NAMESPACE__ . '\Entity\Container',
        __NAMESPACE__ . '\Entity\ExternalInterface' => __NAMESPACE__ . '\Entity\External',
        __NAMESPACE__ . '\Entity\InternalInterface' => __NAMESPACE__ . '\Entity\Internal',
        __NAMESPACE__ . '\Entity\CategoryInterface' => __NAMESPACE__ . '\Entity\Category',
        __NAMESPACE__ . '\Entity\HolderInterface' => __NAMESPACE__ . '\Entity\Holder'
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
    'router' => array(
        'routes' => array(
            'related-content' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/{related-content}',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\RelatedContentController'
                    )
                ),
                'child_routes' => array(
                    'manage' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/:id',
                            'defaults' => array(
                                'action' => 'manage'
                            )
                        )
                    ),
                    'add-internal' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/add-internal/:id',
                            'defaults' => array(
                                'action' => 'addInternal'
                            )
                        )
                    ),
                    'add-category' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/add-category/:id',
                            'defaults' => array(
                                'action' => 'addCategory'
                            )
                        )
                    ),
                    'add-external' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/add-external/:id',
                            'defaults' => array(
                                'action' => 'addExternal'
                            )
                        )
                    ),
                    'remove' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/remove-internal/:id',
                            'defaults' => array(
                                'action' => 'remove'
                            )
                        )
                    ),
                    'order' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/order',
                            'defaults' => array(
                                'action' => 'order'
                            )
                        )
                    )
                )
            )
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'related' => function (ServiceLocatorInterface $sl)
            {
                $instance = new RelatedContentHelper();
                $instance->setRelatedContentManager($sl->getServiceLocator()
                    ->get('RelatedContent\Manager\RelatedContentManager'));
                return $instance;
            }
        )
    )
);