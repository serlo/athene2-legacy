<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
return [
    'router'          => [
        'routes' => [
            'home'        => [
                'type'    => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Application\Controller\IndexController',
                        'action'     => 'index'
                    ]
                ]
            ],
            'application' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/application',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/[:controller[/:action]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults'    => []
                        ]
                    ]
                ]
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'MailMan'    => function ($sm) {
                    $config     = $sm->get('Config');
                    $smtpParams = $config['smtpParams'];

                    $transport = new \Zend\Mail\Transport\Smtp();
                    $options   = new \Zend\Mail\Transport\SmtpOptions($smtpParams);
                    $transport->setOptions($options);

                    return $transport;
                }
        ]
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\IndexController' => 'Application\Controller\IndexController'
        ]
    ]
];