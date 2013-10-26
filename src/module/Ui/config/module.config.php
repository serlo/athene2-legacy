<?php
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;
use Zend\Session\Container;
/**
 *
 *
 *
 *
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
return array(
    'navigation' => array(
        'hydratables' => array(
            'default' => array(
                'hydrators' => array()
            ),
            'top-center' => array(
                'hydrators' => array()
            )
        ),
        'default' => array(
            array(
                'label' => 'dummy',
                'uri' => '#'
            )
        ),
        'top-center' => array(),
        'top-left' => array(
            array(
                'label' => 'Home',
                'uri' => '/',
                'icon' => 'home'
            ),
            array(
                'label' => 'Blog',
                'uri' => '/blog'
            ),
            array(
                'label' => 'About',
                'uri' => '/about'
            ),
            array(
                'label' => 'Participate',
                'uri' => '/participate'
            ),
            array(
                'label' => 'Area 51',
                'route' => 'restricted'
            )
        ),
        'top-right' => array(
            array(
                'label' => '',
                'route' => 'user/dashboard',
                'icon' => 'user',
                'needsIdentity' => true
            ),
            array(
                'label' => '',
                'route' => 'user/settings',
                'icon' => 'wrench',
                'needsIdentity' => true
            ),
            array(
                'label' => 'Sign up',
                'route' => 'user/register',
                'icon' => 'pencil',
                'needsIdentity' => false
            ),
            array(
                'label' => '',
                'route' => 'user/login',
                'icon' => 'log-in',
                'needsIdentity' => false,
            ),
            array(
                'label' => '',
                'route' => 'user/logout',
                'icon' => 'log-out',
                'needsIdentity' => true
            )
        ),
        'footer' => array(
            array(
                'label' => 'Über Serlo',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Auf einen Blick',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Bildungsbegriff',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Blog',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Verein',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Presse',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Impressum',
                        'uri' => '#'
                    )
                )
            ),
            array(
                'label' => 'Mitmachen',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Wie läuft\'s?',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Was kann ich tun?',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Als LehrerIn',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Als StudentIn',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Spenden',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Jobs',
                        'uri' => '#'
                    )
                )
            ),
            array(
                'label' => 'Hilfe',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Frag die Community',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Supportformular',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Problem melden',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Kontakt',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Admins der Fächer',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Kontaktformular',
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
            'Ui\Strategy\PhpRendererStrategy'
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'currentLanguage' => function ($helperPluginManager)
            {
                $plugin = new Ui\View\Helper\ActiveLanguage();
                $languageManager = $helperPluginManager->getServiceLocator()->get('Language\Manager\LanguageManager');
                
                // $translator = $helperPluginManager->getServiceLocator()->get('Zend\I18n\Translator\Translator');
                $plugin->setLanguage($languageManager->getLanguageFromRequest());
                
                return $plugin;
            }
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Ui\Renderer\PhpDebugRenderer' => function (ServiceLocatorInterface $sm)
            {
                $service = new Ui\Renderer\PhpDebugRenderer();
                $service->setResolver($sm->get('Zend\View\Resolver\AggregateResolver'));
                $service->setHelperPluginManager($sm->get('ViewHelperManager'));
                return $service;
            },
            'navigation' => 'Ui\Navigation\DefaultNavigationFactory',
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
                '@scripts',
                '@styles'
            ),
            'options' => array(
                'mixin' => false
            )
        ),
        
        'modules' => array(
            'ui' => array(
                'root_path' => __DIR__ . '/../assets/build',
                'collections' => array(
                    'scripts' => array(
                        'assets' => array(
                            'bower_components/modernizr/modernizr.js',
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
        )
    )
);
