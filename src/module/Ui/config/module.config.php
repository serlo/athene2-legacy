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
return array(
    'navigation' => array(
        'top-left' => array(
            array(
                'label' => 'test1',
                'uri' => '#'
            ),
            array(
                'label' => 'test2',
                'uri' => '#'
            )
        ),
        'top-right' => array(
            array(
                'label' => 'test-bott1',
                'uri' => '#'
            ),
            array(
                'label' => 'test-bott2',
                'uri' => '#'
            )
        ),
        'footer' => array(
            array(
                'label' => 'Some footer link',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Some footer sublink',
                        'uri' => '#'
                    )
                )
            ),
            array(
                'label' => 'Some other footer link',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Some other footer sublink',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Some other footer sublink',
                        'uri' => '#'
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../templates/layout/default.phtml',
            'error/404' => __DIR__ . '/../templates/error/404.phtml',
            'error/index' => __DIR__ . '/../templates/error/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../templates'
        ),
        'strategies' => array(
            'Ui\Strategy\PhpRendererStrategy'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Ui\Renderer\PhpDebugRenderer' => function ($sm)
            {
                $service = new Ui\Renderer\PhpDebugRenderer();
                $service->setResolver($sm->get('Zend\View\Resolver\AggregateResolver'));
                $service->setHelperPluginManager($sm->get('ViewHelperManager'));
                return $service;
            },
            'navigation' => 'Ui\Navigation\DynamicNavigationFactory',
            'top_left_navigation' => 'Ui\Navigation\TopLeftNavigationFactory',
            'top_right_navigation' => 'Ui\Navigation\TopRightNavigationFactory',
            'footer_navigation' => 'Ui\Navigation\FooterNavigationFactory',
            'subject_navigation' => 'Ui\Navigation\SubjectNavigationFactory',
        )
    ),
    'assetic_configuration' => array(
        // 'routes'
        // =>
        // array(),
        'webPath' => realpath('public/assets'),
        'basePath' => 'assets',
        
        'default' => array(
            'assets' => array(
                '@scripts',
                '@styles'
            ),
            'options' => array(
                'mixin' => false
            )
        ),
        
        'modules' => array(
            'ui' => array(
                
                // module
                // root
                // path
                // for
                // yout
                // css
                // and
                // js
                // files
                'root_path' => __DIR__ . '/../assets/build',
                
                // collection
                // od
                // assets
                'collections' => array(
                    'scripts' => array(
                        'assets' => array(
                            'bower_components/requirejs/require.js',
                            'scripts/main.js'
                        )
                    ),
                    'styles' => array(
                        'assets' => array(
                            'styles/main.css'
                        ),
                        'filters' => array(
                            'CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        )
                    ),
                    'main_fonts' => array(
                        'assets' => array(
                            'styles/fonts/*.eot',
                            'styles/fonts/*.woff',
                            'styles/fonts/*.svg',
                            'styles/fonts/*.ttf'
                        ),
                        'options' => array(
                            'move_raw' => true
                        )
                    )
                )
            )
        )
    ),
);