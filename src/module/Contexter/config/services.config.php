<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Contexter;

return [
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Router\Router' => __NAMESPACE__ . '\Factory\RouterFactory',
            __NAMESPACE__ . '\Service\ContextService' => __NAMESPACE__ . '\Factory\ContextServiceFactory'
        ]
    ],
    'di' => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\ContextController'
        ],
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Adapter\EntityPluginControllerAdapter' => [
                    'setLanguageManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Controller\ContextController' => [
                    'setUuidManager' => [
                        'required' => true
                    ],
                    'setManager\ContextManager' => [
                        'required' => true
                    ],
                    'setRouter' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Service\ContextService' => [
                    'setServiceLocator' => [
                        'required' => true
                    ],
                    'setObjectManager' => [
                        'required' => true
                    ],
                    'setClassResolver' => [
                        'required' => true
                    ],
                    'setRouter' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\ContextManagerInterface' => __NAMESPACE__ . '\Manager\ContextManager',
                __NAMESPACE__ . '\Router\RouterInterface' => __NAMESPACE__ . '\Router\Router'
            ],
            __NAMESPACE__ . '\Service\ContextService' => [
                'shared' => false
            ]
        ]
    ]
];