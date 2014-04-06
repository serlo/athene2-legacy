<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ui;

use Ui\View\Helper\Brand;
use Ui\View\Helper\PageHeader;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcRbac\Guard\GuardInterface;

return [
    'navigation'            => [
        'hydratables' => [
            'default'    => [
                'hydrators' => []
            ],
            'top-center' => [
                'hydrators' => []
            ]
        ]
    ],
    'zfctwig'               => [
        'helper_manager' => [
            'invokables' => [
                'partial' => 'Zend\View\Helper\Partial',
            ],
        ]
    ],
    'view_manager'          => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => [
            'athene2-editor' => __DIR__ . '/../templates/editor/layout.phtml',
            'layout/home'    => __DIR__ . '/../templates/layout/serlo-home.phtml',
            'layout/1-col'   => __DIR__ . '/../templates/layout/1-col.phtml',
            'layout/layout'  => __DIR__ . '/../templates/layout/2-col.phtml',
            'layout/3-col'   => __DIR__ . '/../templates/layout/3-col.phtml',
            'error/404'      => __DIR__ . '/../templates/error/404.phtml',
            'error/403'      => __DIR__ . '/../templates/error/403.phtml',
            'error/index'    => __DIR__ . '/../templates/error/index.phtml'
        ],
        'strategies'               => [
            'Zend\View\Strategy\JsonStrategy',
            'Ui\Strategy\PhpRendererStrategy'
        ]
    ],
    'view_helpers'          => [
        'factories'  => [
            'pageHeader' => function ($helperPluginManager) {
                    $config = $helperPluginManager->getServiceLocator()->get('Ui\Options\PageHeaderHelperOptions');
                    return new PageHeader($config);
                },
            'brand'      => function ($helperPluginManager) {
                    $config = $helperPluginManager->getServiceLocator()->get('Ui\Options\BrandHelperOptions');
                    return new Brand($config);
                }
        ],
        'invokables' => [
            'timeago'         => 'Ui\View\Helper\Timeago',
            'registry'        => 'Ui\View\Helper\Registry',
            'currentLanguage' => 'Ui\View\Helper\ActiveLanguage',
            'toAlpha'         => 'Ui\View\Helper\ToAlpha'
        ]
    ],
    'page_header_helper'    => [],
    'service_manager'       => [
        'factories'  => [
            'Ui\Renderer\PhpDebugRenderer'                     => function (ServiceLocatorInterface $sm) {
                    $service = new Renderer\PhpDebugRenderer();
                    $service->setResolver($sm->get('Zend\View\Resolver\AggregateResolver'));
                    $service->setHelperPluginManager($sm->get('ViewHelperManager'));

                    return $service;
                },
            __NAMESPACE__ . '\Options\BrandHelperOptions'      => __NAMESPACE__ . '\Factory\BrandHelperOptionsFactory',
            __NAMESPACE__ . '\Options\PageHeaderHelperOptions' => __NAMESPACE__ . '\Factory\PageHeaderHelperOptionsFactory',
        ],
        'invokables' => [
            //'AsseticCacheBuster' => 'AsseticBundle\CacheBuster\LastModifiedStrategy',
        ]
    ],
    'assetic_configuration' => [
        'webPath'          => realpath('public/assets'),
        'basePath'         => 'assets',
        'default'          => [
            'assets'  => [
                '@libs',
                '@scripts',
                '@styles'
            ],
            'options' => [
                'mixin' => false
            ]
        ],
        'routes'           => [
            'entity/repository/add-revision' => [
                '@libs',
                '@editor_scripts',
                '@styles',
                '@editor_styles'
            ],
            'page/revision/create'           => [
                '@libs',
                '@editor_scripts',
                '@styles',
                '@editor_styles'
            ],
            'blog/post/create'               => [
                '@libs',
                '@editor_scripts',
                '@styles',
                '@editor_styles'
            ],
            'blog/post/update'               => [
                '@libs',
                '@editor_scripts',
                '@styles',
                '@editor_styles'
            ]
        ],
        'modules'          => [
            'ui' => [
                'root_path'   => __DIR__ . '/../../../assets/build',
                'collections' => [
                    'libs'           => [
                        'assets' => [
                            'bower_components/modernizr/modernizr.js',
                            'bower_components/requirejs/require.js',
                        ]
                    ],
                    'scripts'        => [
                        'assets' => [
                            'scripts/main.js'
                        ]
                    ],
                    'styles'         => [
                        'assets'  => [
                            'styles/main.css'
                        ],
                        'filters' => [
                            'CssRewriteFilter' => [
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            ]
                        ]
                    ],
                    'editor_scripts' => [
                        'assets' => [
                            '../node_modules/athene2-editor/build/scripts/editor.js'
                        ]
                    ],
                    'editor_styles'  => [
                        'assets' => [
                            '../node_modules/athene2-editor/build/styles/editor.css'
                        ]
                    ],
                    'main_fonts'     => [
                        'assets'  => [
                            'styles/fonts/*',
                            'styles/fonts/*.woff',
                            'styles/fonts/*.svg',
                            'styles/fonts/*.ttf'
                        ],
                        'options' => [
                            'move_raw' => true
                        ]
                    ],
                    'images'         => [
                        'assets'  => [
                            'images/*'
                        ],
                        'options' => [
                            'move_raw' => true
                        ]
                    ]
                ]
            ]
        ],
        'acceptableErrors' => [
            Application::ERROR_CONTROLLER_NOT_FOUND,
            Application::ERROR_CONTROLLER_INVALID,
            Application::ERROR_ROUTER_NO_MATCH,
            GuardInterface::GUARD_UNAUTHORIZED
        ]
    ]
];