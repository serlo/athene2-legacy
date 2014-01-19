<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Admin;

return [
    'router' => [
        'routes' => [
            'backend' => [
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'options'       => [
                    'route'    => '/backend',
                    'defaults' => [
                        'controller' => 'Admin\Controller\HomeController',
                        'action'     => 'index'
                    ]
                ],
                'may_terminate' => true
            ]
        ]
    ],
    'di'     => [
        'allowed_controllers' => [
            'Admin\Controller\HomeController'
        ],
        'definition'          => [
            'class' => [
                'Admin\Controller\HomeController' => [
                ]
            ]
        ]
    ]
];