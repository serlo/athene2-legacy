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
        'default' => array(
            array(
                'label' => 'Home',
                'route' => 'home'
            ),
        )
    ),
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Route' => array()
        )
    ),
    'router' => array(
        'router_class' => 'Zend\Mvc\Router\Http\TranslatorAwareTreeRouteStack',
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index'
                    )
                )
            ),
            // The
            // following
            // is
            // a
            // route
            // to
            // simplify
            // getting
            // started
            // creating
            // new
            // controllers
            // and
            // actions
            // without
            // needing
            // to
            // create
            // a
            // new
            // module.
            // Simply
            // drop
            // new
            // controllers
            // in,
            // and
            // you
            // can
            // access
            // them
            // using
            // the
            // path
            // /application/:controller/:action
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'MailMan' => function  ($sm)
            {
                $config = $sm->get('Config');
                $smtpParams = $config['smtpParams'];
                
                $transport = new \Zend\Mail\Transport\Smtp();
                $options = new \Zend\Mail\Transport\SmtpOptions($smtpParams);
                $transport->setOptions($options);
                return $transport;
            },
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        )
    ),
    // 'assetic_configuration' => array(
    //     // 'routes'
    //     // =>
    //     // array(),
    //     'webPath' => realpath('public/assets'),
    //     'basePath' => 'assets',
        
    //     'default' => array(
    //         'assets' => array(
    //             '@base_css',
    //             '@html5',
    //             '@jquery',
    //             '@bootstrap',
    //             '@sortable',
    //             '@sortable_css'
    //         ),
    //         'options' => array(
    //             'mixin' => false
    //         )
    //     ),
        
    //     'modules' => array(
    //         'application' => array(
                
    //             // module
    //             // root
    //             // path
    //             // for
    //             // yout
    //             // css
    //             // and
    //             // js
    //             // files
    //             'root_path' => __DIR__ . '/../assets',
                
    //             // collection
    //             // od
    //             // assets
    //             'collections' => array(
                    
    //                 'base_css' => array(
    //                     'assets' => array(
    //                         'css/bootstrap.min.css',
    //                         'css/bootstrap-responsive.min.css',
    //                         'css/generic.css',
    //                         'css/style.css'
    //                     ),
    //                     'filters' => array(
    //                         'CssRewriteFilter' => array(
    //                             'name' => 'Assetic\Filter\CssRewriteFilter'
    //                         )
    //                     ),
    //                     'options' => array()
    //                 ),
                    
    //                 'html5' => array(
    //                     'assets' => array(
    //                         'js/html5.js'
    //                     )
    //                 ),
                    
    //                 'jquery' => array(
    //                     'assets' => array(
    //                         'js/jquery.min.js'
    //                     )
    //                 ),
                    
    //                 'bootstrap' => array(
    //                     'assets' => array(
    //                         'js/bootstrap.min.js'
    //                     )
    //                 ),
                    
    //                 'sortable' => array(
    //                     'assets' => array(
    //                         'js/jquery-sortable.js'
    //                     )
    //                 ),
    //                 'sortable_css' => array(
    //                     'assets' => array(
    //                         'css/jquery-sortable.css'
    //                     )
    //                 ),
                    
    //                 'base_images' => array(
    //                     'assets' => array(
    //                         'images/*.png',
    //                         'img/*.png',
    //                         'images/*.ico'
    //                     ),
    //                     'options' => array(
    //                         'move_raw' => true
    //                     )
    //                 )
    //             )
    //         )
    //     )
    // ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);