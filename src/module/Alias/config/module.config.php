<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Alias;

return [
    'alias_manager'   => [
        'aliases' => [
            'blogPost' => [
                'tokenize' => 'blog/{category}/{title}',
                'provider' => 'Blog\Provider\TokenizerProvider',
                'fallback' => 'blog/{category}/{id}-{title}'
            ],
            'entity'   => [
                'tokenize' => '{path}/{title}',
                'fallback' => '{path}/{type}/{title}-{id}',
                'provider' => 'Entity\Provider\TokenProvider'
            ],
            'taxonomyTerm'   => [
                'tokenize' => '{path}',
                'fallback' => '{path}-{id}',
                'provider' => 'Taxonomy\Provider\TokenProvider'
            ]
        ]
    ],
    'class_resolver'  => [
        __NAMESPACE__ . '\Entity\AliasInterface' => __NAMESPACE__ . '\Entity\Alias'
    ],
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Options\ManagerOptions'             => __NAMESPACE__ . '\Factory\ManagerOptionsFactory',
            __NAMESPACE__ . '\AliasManager'                       => __NAMESPACE__ . '\Factory\AliasManagerFactory',
            __NAMESPACE__ . '\Listener\BlogManagerListener'       => __NAMESPACE__ . '\Factory\BlogManagerListenerFactory',
            __NAMESPACE__ . '\Listener\BlogManagerListener'       => __NAMESPACE__ . '\Factory\BlogManagerListenerFactory',
            __NAMESPACE__ . '\Listener\RepositoryManagerListener' => __NAMESPACE__ . '\Factory\RepositoryManagerListenerFactory',
            __NAMESPACE__ . '\ListenerPageControllerListener'     => __NAMESPACE__ . '\Factory\PageControllerListenerFactory',
            __NAMESPACE__ . '\Storage\AliasStorage'               => __NAMESPACE__ . '\Factory\AliasStorageFactory'
        ]
    ],
    'di'              => [
        'allowed_controllers' => [
            'Alias\Controller\AliasController'
        ],
        'definition'          => [
            'class' => [
                __NAMESPACE__ . '\Controller\AliasController' => [
                    'setAliasManager'    => [
                        'required' => true
                    ],
                    'setInstanceManager' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance'            => [
            'preferences' => [
                __NAMESPACE__ . '\AliasManagerInterface' => __NAMESPACE__ . '\AliasManager'
            ]
        ]
    ],
    'doctrine'        => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ]
            ],
            'orm_default'             => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'view_helpers'    => [
        'factories' => [
            'url' => __NAMESPACE__ . '\Factory\UrlHelperFactory'
        ]
    ]
];
