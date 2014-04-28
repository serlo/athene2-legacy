<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Contexter;

return [
    'router' => [
        'routes' => [
            'contexter' => [
                'type'         => 'Zend\Mvc\Router\Http\Segment',
                'options'      => [
                    'route'    => '/context',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\ContextController'
                    ]
                ],
                'child_routes' => [
                    'select-uri' => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/select-uri',
                            'defaults' => [
                                'action' => 'selectUri'
                            ]
                        ]
                    ],
                    'route'      => [
                        'type'         => 'Zend\Mvc\Router\Http\Segment',
                        'options'      => [
                            'route' => '/route'
                        ],
                        'child_routes' => [
                            'remove' => [
                                'type'    => 'Zend\Mvc\Router\Http\Segment',
                                'options' => [
                                    'route'    => '/remove/:id',
                                    'defaults' => [
                                        'action' => 'removeRoute'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'remove'     => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/remove/:id',
                            'defaults' => [
                                'action' => 'remove'
                            ]
                        ]
                    ],
                    'add'        => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'action' => 'add'
                            ]
                        ]
                    ],
                    'manage'     => [
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => [
                            'route'    => '/manage',
                            'defaults' => [
                                'action' => 'manage'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
