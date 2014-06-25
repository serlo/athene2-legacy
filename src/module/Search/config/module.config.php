<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author       Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license      LGPL-3.0
 * @license      http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link         https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright    Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Search;

return [
    'service_manager' => [
        'invokables' => [
            'Search\Adapter\AdapterPluginManager'
        ],
        'factories'  => [
            __NAMESPACE__ . '\SearchService' => __NAMESPACE__ . '\Factory\SearchServiceFactory'
        ]
    ],
    'di'              => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\SearchController'
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Controller\SearchController' => []
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\SearchServiceInterface' => __NAMESPACE__ . '\SearchService'
            ]
        ]
    ],
    'router'          => [
        'routes' => [
            'search' => [
                'type'          => 'literal',
                'options'       => [
                    'route'    => '/search',
                    'defaults' => [
                        'controller' => __NAMESPACE__ . '\Controller\SearchController',
                        'action'     => 'search'
                    ]
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'ajax' => [
                        'type'    => 'literal',
                        'options' => [
                            'route'    => '/ajax',
                            'defaults' => [
                                'action' => 'ajax'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
