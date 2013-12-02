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
            'ZfcRbac\Guard\ControllerGuard' => [
                [
                    'controller' => 'Entity\Controller\EntityController',
                    'actions' => [
                        'create',
                    ],
                    'roles' => [
                        'login'
                    ]
                ],
                [
                    'controller' => 'Discussion\Controller\DiscussionController',
                    'actions' => [
                        'trash',
                        'restore'
                    ],
                    'roles' => [
                        'moderator'
                    ]
                ],
                [
                    'controller' => 'Discussion\Controller\DiscussionController',
                    'actions' => [
                        'purge',
                    ],
                    'roles' => [
                        'sysadmin'
                    ]
                ],
                [
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => [
                        'history',
                        'compare',
                    ],
                    'roles' => [
                        'guest'
                    ]
                ],
                [
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => [
                        'addRevision',
                    ],
                    'roles' => [
                        'login'
                    ]
                ],
                [
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => [
                        'trashRevision',
                        'checkout'
                    ],
                    'roles' => [
                        'helper'
                    ]
                ],
                [
                    'controller' => 'LearningResource\Plugin\Taxonomy\Controller\TaxonomyController',
                    'actions' => [
                        'update',
                    ],
                    'roles' => [
                        'moderator'
                    ]
                ],
            ],
            'ZfcRbac\Guard\RouteGuard' => [
                'entity/plugin/link/order' => ['moderator'],
            ]
        ]
    ]
];