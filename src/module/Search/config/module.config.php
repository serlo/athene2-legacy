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

use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'search'          => [],
    'service_manager' => [
        'factories' => [
            'Foolz\SphinxQL\Connection'      => function (ServiceLocatorInterface $serviceLocator) {
                    $config   = $serviceLocator->get('config');
                    $config   = $config['sphinx'];
                    $instance = new \Foolz\SphinxQL\Connection();
                    $instance->setConnectionParams($config['host'], $config['port']);
                    return $instance;
                },
            __NAMESPACE__ . '\SearchService' => function (ServiceLocatorInterface $serviceLocator) {
                    $config   = $serviceLocator->get('config');
                    $config   = $config['search'];
                    $instance = new SearchService();
                    $instance->setRouter($serviceLocator->get('Router'));
                    $instance->setServiceLocator($serviceLocator);
                    $instance->setConfig($config);
                    return $instance;
                }
        ]
    ],
    'di'              => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\SearchController'
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Adapter\SphinxQL\EntityAdapter'       => [
                    'setConnection'    => [
                        'required' => true
                    ],
                    'setEntityManager' => [
                        'required' => true
                    ],
                    'setNormalizer'    => [
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
                'type'          => 'Zend\Mvc\Router\Http\Segment',
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
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
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
