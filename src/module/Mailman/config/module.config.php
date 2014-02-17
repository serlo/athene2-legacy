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
namespace Mailman;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @codeCoverageIgnore
 */
return [
    'mailman'         => [
        'adapters' => [
            'Mailman\Adapter\ZendMailAdapter'
        ]
    ],
    'service_manager' => [
        'factories' => [
            'Mailman\Mailman' => function (ServiceLocatorInterface $sm) {
                    $mailman = new \Mailman\Mailman();
                    $mailman->setConfig($sm->get('config')['mailman']);
                    $mailman->setServiceLocator($sm);

                    return $mailman;
                }
        ]
    ],
    'di'              => [
        'definition' => [
            'class' => [
                'Mailman\Listener\UserControllerListener' => [
                    'setMailman'    => [
                        'required' => true
                    ],
                    'setTranslator' => [
                        'required' => true
                    ],
                    'setRenderer'   => [
                        'required' => true
                    ]
                ],
                'Mailman\Listener\AuthenticationControllerListener' => [
                    'setMailman'    => [
                        'required' => true
                    ],
                    'setTranslator' => [
                        'required' => true
                    ],
                    'setRenderer'   => [
                        'required' => true
                    ]
                ],
                'Mailman\Adapter\ZendMailAdapter'         => [
                    'setSmtpOptions' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance'   => [
            'preferences' => [
                'Mailman\MailmanInterface' => 'Mailman\Mailman'
            ]
        ]
    ]
];
