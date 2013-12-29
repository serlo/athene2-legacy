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
namespace Markdown;

use Markdown\View\Helper\MarkdownHelper;
return [
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
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Options\ModuleOptions' => __NAMESPACE__ . '\Factory\ModuleOptionsFactory'
        ]
    ],
    'view_helpers' => [
        'factories' => [
            'markdown' => function ($helperPluginManager)
            {
                $plugin = new MarkdownHelper();
                $renderer = $helperPluginManager->getServiceLocator()->get('Markdown\Service\HtmlRenderService');
                
                $plugin->setRenderService($renderer);
                
                return $plugin;
            }
        ]
    ],
    'di' => [
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Service\CacheService' => [
                    'setClassResolver' => [
                        'required' => true
                    ],
                    'setObjectManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Service\HtmlRenderService' => [
                    'setModuleOptions' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\Service\CacheServiceInterface' => __NAMESPACE__ . '\Service\CacheService',
                __NAMESPACE__ . '\Service\RenderServiceInterface' => __NAMESPACE__ . '\Service\HtmlRenderService'
            ]
        ]
    ],
    'class_resolver' => [
        __NAMESPACE__ . '\Entity\CacheInterface' => __NAMESPACE__ . '\Entity\HtmlCache'
    ]
];