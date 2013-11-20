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
namespace Uuid;

/**
 * @codeCoverageIgnore
 */
return array(
    'class_resolver' => array(
        'Uuid\Entity\UuidInterface' => 'Uuid\Entity\Uuid'
    ),
    'uuid_router' => array(
        'routes' => array()
    ),
    'router' => array(
        'routes' => array(
            'uuid' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => false,
                'options' => array(
                    'route' => '/uuid'
                ),
                'child_routes' => array(
                    'router' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/route/:uuid',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\RouterController',
                                'action' => 'assemble'
                            )
                        )
                    ),
                    'trash' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/trash/:id',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\UuidController',
                                'action' => 'trash'
                            )
                        )
                    ),
                    'recycle-bin' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/recycle-bin',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\UuidController',
                                'action' => 'recycleBin'
                            )
                        )
                    ),
                    'restore' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/restore/:id',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\UuidController',
                                'action' => 'restore'
                            )
                        )
                    ),
                    'purge' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/purge/:id',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\UuidController',
                                'action' => 'purge'
                            )
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Uuid\Router\UuidRouter' => function ($sm)
            {
                $router = new \Uuid\Router\UuidRouter();
                $config = $sm->get('config')['uuid_router'];
                $router->setUuidManager($sm->get('Uuid\Manager\UuidManager'));
                $router->setConfig($config);
                return $router;
            }
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Uuid\Controller\RouterController',
            'Uuid\Controller\UuidController'
        ),
        'definition' => array(
            'class' => array(
                'Uuid\Controller\UuidController' => array(
                    'setUuidManager' => array(
                        'required' => true
                    )
                ),
                'Uuid\Controller\RouterController' => array(
                    'setUuidRouter' => array(
                        'required' => true
                    )
                ),
                'Uuid\Manager\UuidManager' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setClassResolver' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Uuid\Manager\UuidManagerInterface' => 'Uuid\Manager\UuidManager',
                'Uuid\Router\UuidRouterInterface' => 'Uuid\Router\UuidRouter'
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



