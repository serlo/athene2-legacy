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

return [
    'mailman'         => [
        'adapters' => [
            'Mailman\Adapter\ZendMailAdapter'
        ]
    ],
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Mailman'                                   => __NAMESPACE__ . '\Factory\MailmanFactory',
            __NAMESPACE__ . '\Adapter\ZendMailAdapter'                   => __NAMESPACE__ . '\Factory\ZendMailAdapterFactory',
            __NAMESPACE__ . '\Listener\AuthenticationControllerListener' => __NAMESPACE__ . '\Factory\AuthenticationControllerListenerFactory',
            __NAMESPACE__ . '\Listener\UserControllerListener'           => __NAMESPACE__ . '\Factory\UserControllerListenerFactory'
        ]
    ],
    'di'              => [
        'instance' => [
            'preferences' => [
                'Mailman\MailmanInterface' => 'Mailman\Mailman'
            ]
        ]
    ]
];
