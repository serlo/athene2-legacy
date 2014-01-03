<?php
/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ui;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Application;
use Ui\View\Helper\PageHeader;
use ZfcRbac\Guard\GuardInterface;
use Ui\View\Helper\Brand;

return array(
    'di' => [
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Provider\LanguageAwareNavigationProvider' => [
                    'setLanguageManager' => [
                        'required' => true
                    ]
                ]
            ]
        ]
    ],
    'navigation' => array(
        'hydratables' => array(
            'default' => array(
                'hydrators' => array()
            ),
            'top-center' => array(
                'hydrators' => array()
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
            'athene2-editor' => __DIR__ . '/../templates/editor/layout.phtml',
            'layout/home' => __DIR__ . '/../templates/layout/serlo-home.phtml',
            'layout/1-col' => __DIR__ . '/../templates/layout/1-col.phtml',
            'layout/layout' => __DIR__ . '/../templates/layout/2-col.phtml',
            'layout/3-col' => __DIR__ . '/../templates/layout/3-col.phtml',
            'error/404' => __DIR__ . '/../templates/error/404.phtml',
            'error/index' => __DIR__ . '/../templates/error/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../templates'
        ),
        'strategies' => array(
            'Zend\View\Strategy\JsonStrategy',
            'Ui\Strategy\PhpRendererStrategy'
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'currentLanguage' => function ($helperPluginManager)
            {
                $plugin = new View\Helper\ActiveLanguage();
                $languageManager = $helperPluginManager->getServiceLocator()->get('Language\Manager\LanguageManager');
                
                // $translator = $helperPluginManager->getServiceLocator()->get('Zend\I18n\Translator\Translator');
                $plugin->setLanguage($languageManager->getLanguageFromRequest());
                
                return $plugin;
            },
            'pageHeader' => function ($helperPluginManager)
            {
                $config = $helperPluginManager->getServiceLocator()->get('config')['page_header_helper'];
                $plugin = new PageHeader();
                $plugin->setConfig($config);
                return $plugin;
            },
            'brand' => function ($helperPluginManager)
            {
                $config = $helperPluginManager->getServiceLocator()->get('config')['brand'];
                $plugin = new Brand();
                $plugin->setConfig($config);
                return $plugin;
            }
        ),
        'invokables' => array(
            'timeago' => 'Ui\View\Helper\Timeago',
            'registry' => 'Ui\View\Helper\Registry'
        )
    ),
    'page_header_helper' => array(),
    'service_manager' => array(
        'factories' => array(
            'Ui\Renderer\PhpDebugRenderer' => function (ServiceLocatorInterface $sm)
            {
                $service = new Renderer\PhpDebugRenderer();
                $service->setResolver($sm->get('Zend\View\Resolver\AggregateResolver'));
                $service->setHelperPluginManager($sm->get('ViewHelperManager'));
                return $service;
            },
            'navigation' => function (ServiceLocatorInterface $sm)
            {
                // This is neccessary because the ServiceManager would create multiple instances of the factory and thus injecting the RouteMatch wouldn't work
                return $sm->get('Ui\Navigation\DefaultNavigationFactory')->createService($sm);
            },
            'top_left_navigation' => 'Ui\Navigation\TopLeftNavigationFactory',
            'top_right_navigation' => 'Ui\Navigation\TopRightNavigationFactory',
            'top_center_navigation' => 'Ui\Navigation\TopCenterNavigationFactory',
            'footer_navigation' => 'Ui\Navigation\FooterNavigationFactory',
            'subject_navigation' => 'Ui\Navigation\SubjectNavigationFactory'
        )
    ),
    'assetic_configuration' => array(
        'webPath' => realpath('public/assets'),
        'basePath' => 'assets',

        'default' => array(
            'assets' => array(
                '@libs',
                '@scripts',
                '@styles'
            ),
            'options' => array(
                'mixin' => false
            )
        ),

        'routes' => array(
            'entity/repository/add-revision' => array(
                '@libs',
                // 'http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML',
                '@editor_scripts',
                '@styles',
                '@editor_styles'
            )
        ),
        
        'modules' => array(
            'ui' => array(
                'root_path' => __DIR__ . '/../assets/build',
                'collections' => array(
                    'libs' => array(
                        'assets' => array(
                            'bower_components/modernizr/modernizr.js',
                            'bower_components/requirejs/require.js',
                        )
                    ),
                    'scripts' => array(
                        'assets' => array(
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
                    'editor_scripts' => array(
                        'assets' => array(
                            '../node_modules/athene2-editor/build/scripts/editor.js'
                        )
                    ),
                    'editor_styles' => array(
                        'assets' => array(
                            '../node_modules/athene2-editor/build/styles/editor.css'
                        )
                    ),
                    'main_fonts' => array(
                        'assets' => array(
                            'styles/fonts/*',
                            'styles/fonts/*.woff',
                            'styles/fonts/*.svg',
                            'styles/fonts/*.ttf'
                        ),
                        'options' => array(
                            'move_raw' => true
                        )
                    ),
                    'images' => array(
                        'assets' => array(
                            'images/*'
                        ),
                        'options' => array(
                            'move_raw' => true
                        )
                    )
                )
            )
        ),
        'acceptableErrors' => array(
            Application::ERROR_CONTROLLER_NOT_FOUND,
            Application::ERROR_CONTROLLER_INVALID,
            Application::ERROR_ROUTER_NO_MATCH,
            GuardInterface::GUARD_UNAUTHORIZED
        )
    )
);