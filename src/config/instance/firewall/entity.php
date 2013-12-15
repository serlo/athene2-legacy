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
return [
    'zfc_rbac' => [
        'guards' => [
            'ZfcRbac\Guard\RouteGuard' => [
                'entity/plugin/link/order' => [
                    'moderator'
                ],
                'entity/plugin/repository/add-revision' => [
                    'login'
                ],
                'entity/plugin/repository/compare' => [
                    '*'
                ],
                'entity/plugin/repository/history' => [
                    '*'
                ],
                'entity/plugin/repository/checkout' => [
                    'helper'
                ],
                'entity/plugin/taxonomy/update' => [
                    'moderator'
                ],
                'entity/plugin/license/update' => [
                    'admin'
                ],
                'entity/create' => [
                    'login'
                ]
            ],
            'ZfcRbac\Guard\ControllerGuard' => [
                [
                    'controller' => 'Application\Controller\IndexController',
                    'roles' => [
                        'login'
                    ]
                ],
            ]
        ]
    ]
];