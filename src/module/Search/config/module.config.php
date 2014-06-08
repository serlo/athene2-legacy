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
    'search'          => [],
    'service_manager' => [
        'factories' => [
            'Foolz\SphinxQL\Connection'      => __NAMESPACE__ . '\Factory\ConnectionFactory',
            __NAMESPACE__ . '\SearchService' => __NAMESPACE__ . '\Factory\SearchServiceFactory'
        ]
    ],
    'di'              => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\SearchController'
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Adapter\SphinxQL\EntityAdapter'       => [
                    'setConnection'      => [
                        'required' => true
                    ],
                    'setEntityManager'   => [
                        'required' => true
                    ],
                    'setNormalizer'      => [
                        'required' => true
                    ],
                    'setInstanceManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Adapter\SphinxQL\TaxonomyTermAdapter' => [
                    'setConnection'      => [
                        'required' => true
                    ],
                    'setTaxonomyManager' => [
                        'required' => true
                    ],
                    'setNormalizer'      => [
                        'required' => true
                    ],
                    'setInstanceManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Controller\SearchController'          => [
                    'setSearchService' => [
                        'required' => true
                    ]
                ]
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
