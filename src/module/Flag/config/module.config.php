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
namespace Flag;

return array(
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
            __NAMESPACE__ . '\Controller\FlagController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Service\FlagService' => array(
                    'setUserManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Manager\FlagManager' => array(
                    'setClassResolver' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setUuidManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\FlagController' => array(
                    'setFlagManager' => array(
                        'required' => 'true'
                    ),
                    'setUserManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Service\FlagServiceInterface' => __NAMESPACE__ . '\Service\FlagService',
                __NAMESPACE__ . '\Manager\FlagManagerInterface' => __NAMESPACE__ . '\Manager\FlagManager'
            ),
            __NAMESPACE__ . '\Service\FlagService' => array(
                'shared' => false
            )
        )
    ),
    'class_resolver' => array(
        __NAMESPACE__ . '\Entity\FlagInterface' => __NAMESPACE__ . '\Entity\Flag',
        __NAMESPACE__ . '\Entity\TypeInterface' => __NAMESPACE__ . '\Entity\Type',
        __NAMESPACE__ . '\Service\FlagServiceInterface' => __NAMESPACE__ . '\Service\FlagService'
    ),
    'router' => array(
        'routes' => array(
            'flag' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/flag',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\FlagController'
                    )
                ),
                'child_routes' => array(
                    'manage' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/manage[/:type]',
                            'defaults' => array(
                                'action' => 'manage'
                            )
                        )
                    ),
                    'add' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/add/:id',
                            'defaults' => array(
                                'action' => 'add'
                            )
                        )
                    ),
                    'detail' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/detail/:id',
                            'defaults' => array(
                                'action' => 'detail'
                            )
                        )
                    ),
                    'remove' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/remove/:id',
                            'defaults' => array(
                                'action' => 'remove'
                            )
                        )
                    )
                )
            )
        )
    )
);