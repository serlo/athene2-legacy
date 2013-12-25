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
namespace Alias;

return [
    'alias_manager' => [
        'aliases' => [
            'blogPost' => [
                'tokenize' => 'blog/{category}/{title}',
                'provider' => 'Blog\Provider\TokenizerProvider',
                'fallback' => 'blog/{category}/{id}-{title}'
            ],
            'entity' => [
                'tokenize' => '{path}/{title}',
                'fallback' => '{path}/{type}/{title}-{id}',
                'provider' => 'Entity\Provider\TokenProvider'
            ]
        ]
    ],
    'class_resolver' => [
        __NAMESPACE__ . '\Entity\AliasInterface' => __NAMESPACE__ . '\Entity\Alias'
    ],
    'router' => [
        'routes' => [
            'alias' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/alias/:alias',
                    'defaults' => [
                        'controller' => 'Alias\Controller\AliasController',
                        'action' => 'forward'
                    ],
                    'constraints' => [
                        'alias' => '(.)+'
                    ]
                ],
                'may_terminate' => true
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            'Alias\Options\ManagerOptions' => 'Alias\Factory\ManagerOptionsFactory'
        ]
    ],
    'di' => [
        'allowed_controllers' => [
            'Alias\Controller\AliasController'
        ],
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Controller\AliasController' => [
                    'setAliasManager' => [
                        'required' => true
                    ],
                    'setLanguageManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Listener\BlogControllerListener' => [
                    'setAliasManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Listener\PageControllerListener' => [
                    'setAliasManager' => [
                        'required' => true
                    ]
                ],
                'Alias\AliasManager' => [
                    'setClassResolver' => [
                        'required' => true
                    ],
                    'setOptions' => [
                        'required' => true
                    ],
                    'setObjectManager' => [
                        'required' => true
                    ],
                    'setTokenizer' => [
                        'required' => true
                    ],
                    'setUuidManager' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\AliasManagerInterface' => __NAMESPACE__ . '\AliasManager'
            ]
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'view_helpers' => [
        'factories' => [
            'url' => function ($helperPluginManager)
            {
                $serviceLocator = $helperPluginManager->getServiceLocator();
                $view_helper = new \Alias\View\Helper\Url();
                
                $router = \Zend\Console\Console::isConsole() ? 'HttpRouter' : 'Router';
                $view_helper->setRouter($serviceLocator->get($router));
                
                $view_helper->setAliasManager($serviceLocator->get('Alias\AliasManager'));
                $view_helper->setLanguageManager($serviceLocator->get('Language\Manager\LanguageManager'));
                
                $match = $serviceLocator->get('application')
                    ->getMvcEvent()
                    ->getRouteMatch();
                
                $interface = 'Zend\Mvc\Router\\' . (\Zend\Console\Console::isConsole() ? 'Console' : 'Http') . '\RouteMatch';
                
                if ($match instanceof $interface) {
                    $view_helper->setRouteMatch($match);
                }
                
                return $view_helper;
            }
        ]
    ]
];