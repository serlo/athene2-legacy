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
namespace Authorization;

return [
    'zfc_rbac' => [
        'guard_manager' => [
            'factories' => [
                __NAMESPACE__ . '\Guard\AssertiveControllerGuard' => __NAMESPACE__ . '\Factory\AssertiveControllerGuardFactory'
            ]
        ]
    ],
    'di' => [
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Service\AuthorizationService' => [
                    'setAuthorizationService' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Assertion\LanguageAssertion' => [
                    'setLanguageManager' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\Service\AuthorizationServiceInterface' => __NAMESPACE__ . '\Service\AuthorizationService'
            ]
        ]
    ]
];
