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
return [
    'navigation' => [
        'default' => [
            'restricted' => [
                'label' => 'Backend',
                'uri' => '#',
                'pages' => [
                    [
                        'label' => 'Home',
                        'icon' => 'home',
                        'route' => 'backend'
                    ],
                    [
                        'label' => 'Pages',
                        'route' => 'page',
                        'icon' => 'paperclip'
                    ],
                    [
                        'label' => 'Taxonomy',
                        'uri' => '#',
                        'icon' => 'book',
                        'pages' => [
                            [
                                'label' => 'Manage taxonomies',
                                'route' => 'taxonomy/term/organize-all',
                                'pages' => [
                                    [
                                        'route' => 'taxonomy/term/action',
                                        'visible' => false
                                    ],
                                    [
                                        'route' => 'taxonomy/term/create',
                                        'visible' => false
                                    ],
                                    [
                                        'route' => 'taxonomy/term/update',
                                        'visible' => false
                                    ],
                                    [
                                        'route' => 'taxonomy/term/sort-associated',
                                        'visible' => false
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'label' => 'Authorization',
                        'icon' => 'lock',
                        'uri' => '#',
                        'pages' => [
                            [
                                'label' => 'Manage roles',
                                'route' => 'authorization/roles'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Users',
                        'icon' => 'user',
                        'route' => 'users',
                        'pages' => [
                            [
                                'route' => 'authorization/role/show',
                                'visible' => false
                            ]
                        ]
                    ],
                    [
                        'label' => 'Recycle bin',
                        'icon' => 'trash',
                        'route' => 'uuid/recycle-bin'
                    ],
                    [
                        'label' => 'Flags',
                        'icon' => 'flag',
                        'route' => 'flag/manage',
                        'pages' => [
                            [
                                'route' => 'flag/detail',
                                'visible' => false
                            ]
                        ]
                    ],
                    [
                        'label' => 'Licenses',
                        'icon' => 'tags',
                        'route' => 'license/manage',
                        'pages' => [
                            [
                                'route' => 'license/add',
                                'visible' => false
                            ],
                            [
                                'route' => 'license/update',
                                'visible' => false
                            ],
                            [
                                'route' => 'license/detail',
                                'visible' => false
                            ]
                        ]
                    ]
                ]
            ],
            'blog' => [
                'label' => 'Blog',
                'route' => 'blog',
                'pages' => [
                    [
                        'label' => 'Blogs',
                        'route' => 'blog',
                        'icon' => 'home',
                        'pages' => [
                            [
                                'route' => 'blog/post/create',
                                'visible' => false
                            ],
                            [
                                'route' => 'blog/post/view',
                                'visible' => false
                            ]
                        ]
                    ]
                ]
            ],
            'community' => [
                'label' => 'Community',
                'route' => 'discussion',
                'params' => [],
                'pages' => [
                    [
                        'label' => 'Diskussionen',
                        'route' => 'discussion/discussions',
                        'icon' => 'comment'
                    ],
                    [
                        'route' => 'user/login',
                        'visible' => false
                    ],
                    [
                        'route' => 'user/register',
                        'visible' => false
                    ],
                    [
                        'route' => 'user/me',
                        'visible' => false
                    ],
                    [
                        'route' => 'user/profile',
                        'visible' => false
                    ]
                ]
            ],
            'home' => [
                'label' => 'Home',
                'route' => 'home',
                'pages' => [
                    [
                        'route' => 'home',
                        'visible' => false
                    ]
                ]
            ]
        ]
    ]
];
